@php
    if (\Illuminate\Support\Facades\Auth::check()) {
        \App\Models\User::where('id', \Illuminate\Support\Facades\Auth::user()->id)->update([
            'status' => true
        ]);
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Gp Bootstrap Template - Index</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="src/img/favicon.png" rel="icon">
    <link href="src/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="src/vendor/aos/aos.css" rel="stylesheet">
    <link href="src/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="src/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="src/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="src/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="src/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="src/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="src/css/style.css" rel="stylesheet">
</head>
<body>
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center justify-content-lg-between">
    
            <h1 class="logo me-auto me-lg-0">
                <a href="{{ route("dashboard") }}" class="brand-link">
                    <img src="{{ asset("src/img/logo.png") }}" alt="Logo" class="brand-image elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light"><strong>SPM</strong> IT Del</span>
                </a>
            </h1>
          <!-- Uncomment below if you prefer to use an image logo -->
          <!-- <a href="index.html" class="logo me-auto me-lg-0"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>-->
    
          <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>
                <li>
                    <a href="{{ route('news') }}" class="nav-link active">
                        {{-- <i class="nav-icon fas fa-newspaper"></i> --}}
                            {{-- <p> --}}
                                News
                            {{-- </p> --}}
                    </a>
                </li>
              <li class="dropdown"><a href="#"><span>Dashbord</span> <i class="bi bi-chevron-down"></i></a>
                <ul>
                    <li>
                        <a href="{{ route('news') }}" class="nav-link active">
                            {{-- <i class="nav-icon fas fa-newspaper"></i> --}}
                                {{-- <p> --}}
                                    News
                                {{-- </p> --}}
                        </a>
                    </li>
                    @if(\Illuminate\Support\Facades\Auth::check())
                    <li class="nav-item">
                        <a href="{{ route('user-settings') }}" class="nav-link">
                            <i class="fas fa-users nav-icon"></i>
                            {{-- <p> --}}
                                Users Settings
                            {{-- </p> --}}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('announcement') }}" class="nav-link">
                            <i class="nav-icon fas fa-bell"></i>
                            {{-- <p> --}}
                                Announcement
                            {{-- </p> --}}
                        </a>
                    </li>

                    @endif
                    <li class="nav-item">
                        <a href="{{ route('documentManagement') }}" class="nav-link">
                            {{-- <i class="fas fa-file nav-icon"></i> --}}
                            {{-- <p> --}}
                                Document Management
                            {{-- </p> --}}
                        </a>
                    </li>

                </ul>
              </li>
              <li><a class="nav-link scrollto" href="{{ route('news') }}">news</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
          </nav><!-- .navbar -->

            @if(!\Illuminate\Support\Facades\Auth::check())
            {{-- <li class="nav-item ml-3"> --}}
                <a href="{{ route("login") }}" class="btn get-started-btn scrollto">
                    Login
                </a>
            {{-- </li> --}}

            @else
                <li class="nav-item dropdown">
                    <a style="text-decoration: none" data-toggle="dropdown">
                        <div class="user-panel d-flex" style="margin-top: 2px">
                            <div class="image">
                                @if(auth()->user()->profile_pict == null)
                                    <img src="{{ asset('src/img/default-profile-pict.png') }}" class="img-circle custom-border" alt="User Image">
                                @else
                                    <img src="{{ asset(auth()->user()->profile_pict) }}" class="img-circle custom-border" alt="User Image">
                                @endif
                            </div>
                            <div type="button" class="info">
                                <span class="d-block">{{ auth()->user()->name }}</span>
                            </div>
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="{{ route('edit-profile') }}" class="dropdown-item">
                            <i class="mr-2 fas fa-user" style="padding-right: 1px"></i> Profile
                        </a>

                        <div class="dropdown-divider"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt mr-2" ></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            @endif
          {{-- <a href="#about" class="">Get Started</a> --}}
    
        </div>
      </header>


</body>

</html>


  <!-- Vendor JS Files -->
  <script src="src/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="src/vendor/aos/aos.js"></script>
  <script src="src/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="src/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="src/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="src/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="src/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="src/js/main.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(function(navLink) {
            navLink.addEventListener('click', function() {
                document.getElementById('announcementCounter').style.display = 'none';
            });
        });
    });
</script>