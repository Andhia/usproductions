<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Inertia\Inertia;
use App\Models\Service;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['service', 'paymentProof'])->orderBy('date', 'asc')->get();

        return Inertia::render('Admin/Booking/Index', [
            'bookings' => $bookings,
        ]);
    }


    public function create()
    {
        $services = Service::all();
        $bookings = Booking::all();

        return Inertia::render('Booking', [
            'bookings' => $bookings,
            'services' => $services,
        ]);
    }

    public function store(Request $request)
{
    try {
        // Validasi input
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'date' => 'required|date',
            'email' => 'required|email|max:255',
            'service_id' => 'required|exists:services,id',
        ]);

        // Tambah status default booking
        $validated['status'] = 1;

        // Simpan data booking
        $booking = Booking::create();

        // Redirect langsung ke halaman pembayaran (CustomerController@getPayment)
        return redirect()->route('payment.get', ['id' => $booking->id]);

    } catch (\Exception $e) {
        Log::error('Booking Error: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan saat menyimpan booking.');
    }
}

    public function confirmPayment($bookingId)
    {
        $booking = Booking::with(['service'])->find($bookingId);

        $booking->status = 2;
        $booking->save();

        $customerName = $booking->customer_name;
        $customerEmail = $booking->email;
        $serviceName = $booking->service->name;
        $bookingDate = $booking->date;
        $bookingId = $booking->id;

        $emailBody = "Halo {$customerName},\n\n" .
            "Pembayaran Anda telah dikonfirmasi! Berikut adalah detail booking Anda:\n\n" .
            "Nomor Antrian: {$bookingId}\n" .
            "Tanggal: {$bookingDate}\n" .
            "Layanan: {$serviceName}\n\n" .
            "Terima kasih telah memilih layanan kami. Sampai jumpa di barbershop!";

        $client = new Client();
        $response = $client->post(env('PROVIDER_API') . '/email/send', [
            'json' => [
                'email' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'to' => $customerEmail,
                'subject' => 'Konfirmasi Pembayaran - Booking Barbershop',
                'text' => $emailBody
            ]
        ]);
    }

    public function destroy($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        // Hapus data
        $booking->delete();

        return redirect()->route('booking.index');
    }
}