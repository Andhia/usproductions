// components/OurBarbers.jsx
import React, { useState, useRef, useEffect } from "react";

const OurBarbers = () => {
    const [isVisible, setIsVisible] = useState(false);
    const sectionRef = useRef(null);

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    setIsVisible(true);
                }
            },
            { threshold: 0.2 }
        );

        if (sectionRef.current) {
            observer.observe(sectionRef.current);
        }

        return () => observer.disconnect();
    }, []);

    return (
        <section
            ref={sectionRef}
            className="py-16 lg:py-24 bg-gradient-to-br from-white to-cream relative overflow-hidden"
        >
            <div className="container mx-auto px-4 sm:px-6 lg:px-8">
                {/* Header Section */}
                <div
                    className={`text-center mb-16 transition-all duration-1000 ${
                        isVisible
                            ? "opacity-100 translate-y-0"
                            : "opacity-0 translate-y-10"
                    }`}
                >
                    <span className="text-brown text-sm font-semibold tracking-wider uppercase mb-2 block">
                        Tim Profesional
                    </span>
                    <h2 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-800 mb-6">
                        <span className="text-brown">Kapster</span> Kami
                    </h2>
                    <div className="w-24 h-1 bg-brown mx-auto mb-6"></div>
                    <p className="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Saat ini belum tersedia.
                    </p>
                </div>
            </div>

            {/* Background Decorations */}
            <div className="absolute inset-0 overflow-hidden pointer-events-none">
                <div className="absolute top-10 right-10 w-32 h-32 bg-brown opacity-5 rounded-full"></div>
                <div className="absolute bottom-20 left-10 w-24 h-24 bg-brown opacity-5 rounded-full"></div>
            </div>
        </section>
    );
};

export default OurBarbers;
