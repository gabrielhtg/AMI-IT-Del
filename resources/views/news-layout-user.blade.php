<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News More</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("plugins/fontawesome-free/css/all.min.css") }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset("plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css") }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset("plugins/icheck-bootstrap/icheck-bootstrap.min.css") }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset("plugins/jqvmap/jqvmap.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("dist/css/adminlte.min.css") }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset("plugins/overlayScrollbars/css/OverlayScrollbars.min.css") }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset("plugins/daterangepicker/daterangepicker.css") }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset("plugins/summernote/summernote-bs4.min.css") }}">
    <link rel="stylesheet" href="{{ asset("src/css/custom.css") }}">
    <link rel="stylesheet" href="{{ asset("splide/dist/css/splide.min.css") }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    

    <section id="news-view1" class="">
        <div class="container" data-aos="fade-up">
            <div class="col-sm-6">
                <h1 class="ml-1">{{$newsdetail->title }}</h1>
                <p class="mt-3 fst-italic" style="martin-left:5px" >
                <hr class="mx-2" style="border-top: 3px solid black; width: 200%;">
                    {{ $newsdetail->created_at->format('Y-m-d') }}
                </p>
                <hr class="mx-2" style="border-top: 3px solid black; width: 200%;">
            </div>
        
       
            @if(!empty($newsdetail->bgimage))
                    <div class="position-relative overflow-hidden rounded">
                        <img src="{{ asset('src/gambarnews/'.$newsdetail->bgimage) }}" class="img-fluid rounded img-zoomin" style="width: 100vw; height: 400px; object-fit: contain;" alt="">
                    </div>
                    
                    <hr class="mx-1" style="border-top: 3px solid black; width: auto;">

                    <div class="">
                        {!! $newsdetail->description !!}
                    </div>

            @else
            <div class="text-center">
                <h1>Berita Tidak Tersedia</h1>
            </div>
            @endif
        </div>
      </section><!-- End About Section -->
      <!-- /.content-wrapper -->




{{-- -------------------------------JS---------------------------------------- --}}

    <!-- jQuery -->
<script src="{{ asset("plugins/jquery/jquery.min.js") }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset("plugins/jquery-ui/jquery-ui.min.js") }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset("plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
<!-- ChartJS -->
<script src="{{ asset("plugins/chart.js/Chart.min.js") }}"></script>
<!-- Sparkline -->
<script src="{{ asset("plugins/sparklines/sparkline.js") }}"></script>
<!-- JQVMap -->
<script src="{{ asset("plugins/jqvmap/jquery.vmap.min.js") }}"></script>
<script src="{{ asset("plugins/jqvmap/maps/jquery.vmap.usa.js") }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset("plugins/jquery-knob/jquery.knob.min.js") }}"></script>
<!-- daterangepicker -->
<script src="{{ asset("plugins/moment/moment.min.js") }}"></script>
<script src="{{ asset("plugins/daterangepicker/daterangepicker.js") }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset("plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js") }}"></script>
<!-- Summernote -->
<script src="{{ asset("plugins/summernote/summernote-bs4.min.js") }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset("plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js") }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset("dist/js/adminlte.js") }}"></script>
<!-- AdminLTE for demo purposes -->
{{--<script src="{{ asset("dist/js/demo.js") }}"></script>--}}
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset("dist/js/pages/dashboard.js") }}"></script>
<script src="{{ asset('splide/dist/js/splide.min.js') }}"></script>
<script>
    var splide = new Splide( '.splide', {
        type   : 'loop',
        perPage: 3,
        perMove: 1,
        gap: 20,
        breakpoints: {
            640: {
                perPage: 1,
            },
        },
    } );

    splide.mount();
</script>

<script>
function animateValue(id, start, end, duration) {
    var range = end - start;
    var current = start;
    var increment = end > start ? 1 : -1;
    var stepTime = Math.abs(Math.floor(duration / range));
    var obj = document.getElementById(id);
    var timer = setInterval(function() {
      current += increment;
      obj.innerHTML = current;
      if (current == end) {
        clearInterval(timer);
      }
    }, stepTime);
  }

  function startCounters() {
    animateValue("teacherCount", 0, 1500, 3000);
    animateValue("memberCount", 0, 71, 3000);
    animateValue("facultyCount", 0, 4, 3000);
    animateValue("departmentCount", 0, 9, 3000);
  }

  window.addEventListener('load', startCounters);
</script>
<script>
  $(document).ready(function() {
    var keteranganWidth = $('#keteranganContainer')[0].scrollWidth; // Mengukur lebar konten secara keseluruhan
    $('#keteranganContainer').append('<hr class="my-3" style="border-top: 2px solid black; width: ' + keteranganWidth + 'px;">'); // Menambahkan garis dengan lebar sesuai dengan lebar konten
  });
</script>
</body>
</body>
</html>