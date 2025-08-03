<?php

// Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'date', 'email', 'service_id', 'status'];

    // Relationship: A booking belongs to a service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    // Relationship: A booking has one payment proof
    public function paymentProof()
    {
        return $this->hasOne(PaymentProof::class);
    }
}


