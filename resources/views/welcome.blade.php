<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="UTF-8">
<title>{{ \App\Models\Setting::get('app_name', 'Jurnal 7 Kebiasaan Anak Indonesia Hebat') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Sistem pencatatan pembiasaan harian peserta didik sebagai penguatan karakter">

<link rel="icon" href="{{ asset(\App\Models\Setting::get('app_favicon', 'favicon.ico')) }}">

<!-- Bootstrap 5 & AOS Animation -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<!-- Google Fonts: Inter -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
  --kemdik-blue: #0B5ED7;
  --kemdik-dark: #0A1F44;
  --kemdik-light: #E7F1FF;
  --kemdik-yellow: #FFC107;
  --kemdik-accent: #0d6efd;
}

body {
  font-family: "Inter", sans-serif;
  overflow-x: hidden;
}

/* NAVBAR */
.navbar {
  background: rgba(11, 94, 215, 0.95);
  backdrop-filter: blur(10px);
  transition: .3s;
}
.navbar-brand {
  font-weight: 700;
  letter-spacing: -0.5px;
}
.nav-link {
  color: #fff !important;
  opacity: .9;
  font-weight: 500;
  transition: .3s;
}
.nav-link:hover {
  opacity: 1;
  transform: translateY(-1px);
}

/* HERO */
.hero {
  min-height: 100vh;
  padding-top: 80px; /* Offset for navbar */
  background: linear-gradient(135deg, var(--kemdik-blue), var(--kemdik-dark));
  color: #fff;
  display: flex;
  align-items: center;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero h1 {
  font-size: clamp(2.5rem, 5vw, 3.5rem);
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 1.5rem;
}
.hero p {
  font-size: 1.15rem;
  max-width: 760px;
  margin: 0 auto 2.5rem;
  opacity: .9;
  font-weight: 300;
}
.hero-content {
    position: relative;
    z-index: 2;
}

/* BUTTON */
.btn-kemdik {
  background: var(--kemdik-yellow);
  color: #000;
  font-weight: 700;
  padding: 14px 32px;
  border-radius: 50px;
  border: 2px solid var(--kemdik-yellow);
  transition: all .3s ease;
  box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
}
.btn-kemdik:hover {
  background: transparent;
  color: var(--kemdik-yellow);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
}
.btn-outline-light-custom {
    border: 2px solid rgba(255,255,255,0.3);
    color: #fff;
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 600;
    margin-left: 10px;
    transition: .3s;
}
.btn-outline-light-custom:hover {
    background: rgba(255,255,255,0.1);
    border-color: #fff;
    color: #fff;
}

/* STATS */
.stats-section {
    background: #fff;
    margin-top: -60px;
    position: relative;
    z-index: 10;
    border-radius: 20px 20px 0 0;
    box-shadow: 0 -10px 30px rgba(0,0,0,0.05);
}
.stat-card h3 {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--kemdik-blue);
    margin-bottom: 0;
}
.stat-card p {
    color: #6c757d;
    font-weight: 500;
}

/* SECTION */
.section {
  padding: 100px 0;
}
.section-title {
  font-weight: 800;
  color: var(--kemdik-dark);
  margin-bottom: 1rem;
}
.section-subtitle {
    color: var(--kemdik-blue);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.9rem;
    display: block;
    margin-bottom: 10px;
}

/* 7 KEBIASAAN */
.kebiasaan-card {
  background: #fff;
  border-radius: 20px;
  padding: 40px 25px;
  box-shadow: 0 10px 30px rgba(0,0,0,.03);
  transition: all .4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  height: 100%;
  border: 1px solid rgba(0,0,0,0.02);
}
.kebiasaan-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(11, 94, 215, 0.1);
  border-color: var(--kemdik-light);
}
.icon-circle {
  width: 80px;
  height: 80px;
  background: var(--kemdik-light);
  color: var(--kemdik-blue);
  border-radius: 50%;
  font-size: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  transition: .4s;
}
.kebiasaan-card:hover .icon-circle {
    background: var(--kemdik-blue);
    color: #fff;
    transform: rotateY(180deg);
}
.kebiasaan-card strong {
    font-size: 1.1rem;
    color: var(--kemdik-dark);
}

/* ALUR */
.alur-step {
  background: #fff;
  padding: 40px 30px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,.05);
  position: relative;
  overflow: hidden;
  height: 100%;
}
.alur-step::after {
    content:'';
    position: absolute;
    bottom: 0; left: 0; width: 100%; height: 4px;
    background: var(--kemdik-blue);
    transform: scaleX(0);
    transition: .4s;
}
.alur-step:hover::after {
    transform: scaleX(1);
}
.alur-step h5 {
    font-weight: 700;
    color: var(--kemdik-dark);
    margin-bottom: 15px;
}

/* CTA */
.cta-section {
    background: var(--kemdik-blue);
    color: #fff;
    position: relative;
}
.cta-section::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%239C92AC' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
    opacity: 0.5;
}

/* FOOTER */
footer {
  background: var(--kemdik-dark);
  color: #adb5bd;
  padding: 40px 0 20px;
  font-size: 14px;
}
footer h6 {
    color: #fff;
    font-weight: 700;
    margin-bottom: 20px;
}
footer a {
    color: #adb5bd;
    text-decoration: none;
    transition: .3s;
}
footer a:hover {
    color: var(--kemdik-yellow);
}

/* RESPONSIVE MEDIA QUERIES */
@media (max-width: 991.98px) {
  .hero {
    padding-top: 100px;
    padding-bottom: 60px;
    min-height: auto; /* Allow auto height on mobile */
    text-align: center;
  }
  .hero h1 {
    font-size: 2.2rem; /* Smaller font on mobile */
  }
  .hero p {
    font-size: 1rem;
  }
  .stats-section {
    margin-top: 20px; /* Reset negative margin to prevent overlap */
  }
  .section {
    padding: 60px 0;
  }
}

@media (max-width: 575.98px) {
  .hero h1 {
    font-size: 1.8rem;
  }
  .btn-kemdik, .btn-outline-light-custom {
    width: 100%; /* Full width buttons on small screens */
    margin: 5px 0;
    margin-left: 0 !important;
    display: block;
  }
  .d-flex.justify-content-center.gap-2 {
    flex-direction: column; /* Stack buttons vertically */
  }
  .navbar-brand {
    font-size: 1.1rem;
  }
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand text-white d-flex align-items-center" href="#">
        <img src="{{ asset(\App\Models\Setting::get('app_favicon', 'template-admin/assets/images/favicon.ico')) }}" 
             width="32" height="32" class="me-2 rounded-circle bg-white p-1" alt="Logo">
        7KAIH
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
        <li class="nav-item"><a class="nav-link" href="#kebiasaan">7 Kebiasaan</a></li>
        <li class="nav-item"><a class="nav-link" href="#alur">Alur</a></li>
        <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
          @if (Route::has('login'))
              @auth
                  <a href="{{ url('/dashboard') }}" class="btn btn-kemdik btn-sm px-4">Dashboard</a>
              @else
                  <a href="{{ route('login') }}" class="btn btn-kemdik btn-sm px-4">Login</a>
              @endauth
          @endif
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero d-flex align-items-center">
  <div class="container hero-content">
    <div data-aos="fade-up" data-aos-duration="1000">
        <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">Platform Penguatan Karakter</span>
        <h1>{{ \App\Models\Setting::get('app_name', 'Jurnal 7 Kebiasaan Anak Indonesia Hebat') }}</h1>
        <p class="mx-auto">
        Kunci kesuksesan bukan hanya pada kecerdasan akademis, <br>
        tetapi pada karakter yang kuat dan kebiasaan positif yang dibangun sejak dini.
        </p>
        
        <div class="d-flex justify-content-center gap-2">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-kemdik btn-lg">Ke Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-kemdik btn-lg">Mulai Sekarang</a>
                @endauth
            @endif
            <a href="#tentang" class="btn btn-outline-light-custom btn-lg">Pelajari Lebih Lanjut</a>
        </div>
    </div>
  </div>
</section>

<!-- STATS SECTION -->
<div class="container stats-section p-4">
    <div class="row text-center">
        <div class="col-md-4 mb-3 mb-md-0" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card">
                <h3 class="counter">{{ $schools_count ?? '0' }}</h3>
                <p class="mb-0">Sekolah Bergabung</p>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card border-start border-end">
                <h3 class="counter">{{ $teachers_count ?? '0' }}</h3>
                <p class="mb-0">Guru Terdaftar</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card">
                <h3 class="counter">{{ $students_count ?? '0' }}</h3>
                <p class="mb-0">Siswa Aktif</p>
            </div>
        </div>
    </div>
</div>

<!-- TENTANG -->
<section id="tentang" class="section">
  <div class="container text-center">
    <div data-aos="fade-up">
        <span class="section-subtitle">Tentang Aplikasi</span>
        <h2 class="section-title">Membangun Generasi Emas</h2>
        <p class="text-muted mx-auto" style="max-width:800px; font-size: 1.1rem; line-height: 1.8;">
        Aplikasi ini hadir sebagai solusi digital untuk mencatat, memantau, dan mengevaluasi 
        pelaksanaan <strong>7 Kebiasaan Anak Indonesia Hebat</strong>. 
        Dirancang agar ramah anak dan mudah digunakan, aplikasi ini menghubungkan 
        Siswa, Guru, dan Orang Tua dalam satu ekosistem pendidikan karakter yang terintegrasi.
        </p>
    </div>
  </div>
</section>

<!-- 7 KEBIASAAN -->
<section id="kebiasaan" class="section" style="background:var(--kemdik-light)">
  <div class="container">
    <div class="text-center mb-5" data-aos="zoom-in">
        <span class="section-subtitle">Inti Program</span>
        <h2 class="section-title">7 Kebiasaan Anak Indonesia Hebat</h2>
        <p class="text-muted">Pilar utama pembentukan karakter siswa</p>
    </div>

    <div class="row g-4 justify-content-center">
        @php
            $habits = [
                ['icon' => '🌅', 'title' => 'Bangun Pagi', 'desc' => 'Memulai hari dengan semangat dan kesiapan mental.'],
                ['icon' => '🙏', 'title' => 'Beribadah', 'desc' => 'Meningkatkan keimanan dan ketakwaan kepada Tuhan.'],
                ['icon' => '🏃', 'title' => 'Berolahraga', 'desc' => 'Menjaga kesehatan fisik untuk menunjang aktivitas.'],
                ['icon' => '🥗', 'title' => 'Makan Sehat', 'desc' => 'Asupan gizi seimbang untuk tubuh yang kuat.'],
                ['icon' => '📚', 'title' => 'Gemar Belajar', 'desc' => 'Menumbuhkan rasa ingin tahu dan wawasan luas.'],
                ['icon' => '🤝', 'title' => 'Bermasyarakat', 'desc' => 'Peduli lingkungan dan bersosialisasi dengan baik.'],
                ['icon' => '😴', 'title' => 'Tidur Cepat', 'desc' => 'Istirahat cukup untuk memulihkan energi.'],
            ];
        @endphp

        @foreach($habits as $index => $habit)
        <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
            <div class="kebiasaan-card text-center d-flex flex-column h-100">
                <div class="icon-circle shadow-sm">{{ $habit['icon'] }}</div>
                <strong class="d-block mb-2">{{ $habit['title'] }}</strong>
                <p class="small text-muted mb-0 flex-grow-1">{{ $habit['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
  </div>
</section>

<!-- ALUR -->
<section id="alur" class="section">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
        <span class="section-subtitle">Cara Kerja</span>
        <h2 class="section-title">Alur Penggunaan Mudah</h2>
    </div>
    
    <div class="row g-4 text-center">
      <div class="col-md-4" data-aos="fade-right" data-aos-delay="100">
        <div class="alur-step">
          <div class="display-4 text-primary mb-3">01</div>
          <h5>Login Pengguna</h5>
          <p class="text-muted">Siswa, Guru, dan Orang Tua masuk menggunakan akun yang telah didaftarkan oleh sekolah.</p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
        <div class="alur-step">
          <div class="display-4 text-primary mb-3">02</div>
          <h5>Isi Jurnal Harian</h5>
          <p class="text-muted">Siswa mengisi checklist 7 kebiasaan setiap hari secara jujur dan mandiri.</p>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-left" data-aos-delay="300">
        <div class="alur-step">
          <div class="display-4 text-primary mb-3">03</div>
          <h5>Monitoring & Evaluasi</h5>
          <p class="text-muted">Guru dan Orang Tua memantau perkembangan karakter siswa melalui grafik dan laporan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="section cta-section text-center">
    <div class="container position-relative z-2">
        <h2 class="fw-bold mb-3 display-5" data-aos="zoom-in">Siap Mencetak Generasi Hebat?</h2>
        <p class="lead mb-4 op-75" data-aos="fade-up" data-aos-delay="100">
            Mari bersinergi wujudkan profil Pelajar Pancasila yang berkarakter kuat.
        </p>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-light text-primary btn-lg fw-bold rounded-pill shadow-lg px-5" data-aos="fade-up" data-aos-delay="200">Akses Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-kemdik btn-lg px-5" data-aos="fade-up" data-aos-delay="200">Mulai Sekarang</a>
            @endauth
        @endif
    </div>
</section>

<!-- FOOTER -->
<footer class="text-center">
  <div class="container">
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
             <h5 class="text-white fw-bold mb-3">7KAIH</h5>
             <p class="text-white-50 small">
                Aplikasi ini dikembangkan untuk mendukung program penguatan pendidikan karakter di lingkungan satuan pendidikan Indonesia.
             </p>
        </div>
    </div>
    <hr class="border-secondary opacity-25">
    <div class="py-2">
        <small class="text-white-50">
            © {{ date('Y') }} <strong>{{ \App\Models\Setting::get('app_name', 'Jurnal 7 Kebiasaan') }}</strong>. 
            Dikembangkan oleh <strong>Ozan Project</strong>   untuk Pendidikan Indonesia.
        </small>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    once: true,
    offset: 50,
    duration: 800,
    easing: 'ease-out-cubic',
    disable: 'mobile'
  });
</script>
</body>
</html>
