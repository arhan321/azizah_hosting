@extends('layouts.app')
@section('title', 'Konsultasi')

@section('content')
<div class="container py-5 text-center">
    <div class="mx-auto" style="max-width:500px">
        <i class="bi bi-whatsapp text-success display-1 mb-3"></i>
        <h2 class="fw-bold mb-2" style="font-family:'Cinzel',serif">Konsultasi Gratis</h2>
        <p class="text-muted mb-4">
            Hubungi kami langsung melalui WhatsApp untuk konsultasi desain, estimasi harga, dan jadwal pengerjaan.
        </p>
        <a href="https://wa.me/6289630430245?text=Halo%20Aqlam%20Mural%20Kaligrafi,%20saya%20ingin%20konsultasi%20tentang%20pesanan%20kaligrafi%20dan%20mural."
   target="_blank"
   class="btn btn-success btn-lg px-5">

    <i class="bi bi-whatsapp me-2"></i>

    Hubungi via WhatsApp

</a>
        <p class="text-muted mt-4 small">
            <i class="bi bi-clock me-1"></i> Jam operasional: Senin – Jumat, 09.00 – 21.00 WIB
        </p>
    </div>
</div>
@endsection
