<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Perpus</title>
    <meta content="" name="description">

    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="/assets/perpus/assets/img/perpus.png" rel="icon">
    <link href="/assets/perpus/assets/img/perpus.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/assets/perpus/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/perpus/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/perpus/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="/assets/perpus/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/assets/perpus/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="/assets/perpus/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/assets/perpus/assets/css/style.css" rel="stylesheet">
    @yield('ext-css')

</head>

<body>
<!-- ============================================================================================================ -->
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top">
        <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

            <a href="/" class="logo d-flex align-items-center">
                <img src="/assets/perpus/assets/img/logoperpus.png" alt="">
            </a>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a href="/file">Buku</a></li>
                    <li><a href="/file">Multimedia</a></li>
                    <li><a href="/panduan">Panduan</a></li>
                    <li><a class="getstarted scrollto" href="/login">Masuk</a></li>
                    <a href="profile.html"><i class="bi bi-person-circle"></i></a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->

        </div>
    </header><!-- End Header -->

<!-- ============================================================================================================ -->
<!-- ======= Isi ======= -->
@yield('container')
<!-- ============================================================================================================ -->
    
    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>Perpustakaan Online</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="/assets/perpus/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="/assets/perpus/assets/vendor/aos/aos.js"></script>
    <script src="/assets/perpus/assets/vendor/php-email-form/validate.js"></script>
    <script src="/assets/perpus/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/perpus/assets/vendor/purecounter/purecounter.js"></script>
    <script src="/assets/perpus/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="/assets/perpus/assets/vendor/glightbox/js/glightbox.min.js"></script>

    <!-- Template Main JS File -->
    <script src="/assets/perpus/assets/js/main.js"></script>
    @yield('ext-js')

</body>

</html>