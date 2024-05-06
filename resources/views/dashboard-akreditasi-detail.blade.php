
@php use App\Services\CustomConverterService; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Detail</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="shortcut icon" type="image/jpg" href="{{ asset("src/img/logo.png") }}"/>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("plugins/fontawesome-free/css/all.min.css") }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset("plugins/datatables-bs4/css/dataTables.bootstrap4.min.css") }}">
    <link rel="stylesheet" href="{{ asset("plugins/datatables-responsive/css/responsive.bootstrap4.min.css") }}">
    <link rel="stylesheet" href="{{ asset("plugins/datatables-buttons/css/buttons.bootstrap4.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("dist/css/adminlte.min.css") }}">
    <link rel="stylesheet" href="{{ asset("src/css/custom.css") }}">
    <!-- SummerNote -->
    <link rel="stylesheet" href="{{ asset("plugins/summernote/summernote-bs4.min.css") }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    @include("components.navbar")
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include("components.sidebar")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Akreditasi Detail</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <section class="content" id="main-content">
                    <div class="page-header">
                        <span>Judul Hero : </span>
                        <h2>{{ $akreditasidetail->judulakreditasi }}</h2>
                    </div>
                
                    <div class="pengumuman-view">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-10 mt-4">
                                    <div class="box box-solid">
                                        <p>foto akreditasi :</p>
                                        <img style="width:600px;height:400px;" src="{{ asset('src/gambarakreditasi/'.$akreditasidetail->gambarakreditasi) }}" alt="gambar tidak ditemukan" class="img-fluid" style="width: 500px;">
                                    </div>
                                </div>
                                <div id="grid-system1" class="col-sm-10 mt-2">
                                    <div class="box box-solid">
                                        <div id="grid-system1-body" class="box-body">
                                            <span style="display: block">Keterangan Hero : </span> 
                                            {!! $akreditasidetail->keteranganakreditasi !!}
                                        </div>
                                    </div>
                                </div>

                                <div id="grid-system2" class="col-sm-12 mt-5">
                                    <div class="box box-solid">
                                        <div id="grid-system2-body" class="box-body">
                                            <table class="table">
                                                <thead>
                                                    <tr><th>Nama File:</th>
                                                    <th>Size:</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <a href="{{ asset('src/gambarakreditasi/'.$akreditasidetail->gambarakreditasi) }}" target="_blank">
                                                                {{ $akreditasidetail->gambarakreditasi }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @if($fileSizeInMB < 1)
                                                                {{ $fileSizeInKB }} KB
                                                            @else
                                                                {{ $fileSizeInMB }} MB
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                               
                                            <div style="border-top: 3px solid red; padding-top: 10px; margin-top:50px;">

                                                <div style="margin-top:0px">
                                                    <p>ttd,</p>
                                                    {{ $loggedInUserName }}
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    @include('components.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset("plugins/jquery/jquery.min.js") }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset("plugins/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{{ asset("plugins/datatables/jquery.dataTables.min.js") }}}"></script>
<script src="{{ asset("plugins/datatables-bs4/js/dataTables.bootstrap4.min.js") }}"></script>
<script src="{{ asset("plugins/datatables-responsive/js/dataTables.responsive.min.js") }}"></script>
<script src="{{ asset("plugins/datatables-responsive/js/responsive.bootstrap4.min.js") }}"></script>
<script src="{{ asset("plugins/datatables-buttons/js/dataTables.buttons.min.js") }}"></script>
<script src="{{ asset("plugins/datatables-buttons/js/buttons.bootstrap4.min.js") }}"></script>
<script src="{{ asset("plugins/jszip/jszip.min.js") }}"></script>
<script src="{{ asset("plugins/pdfmake/pdfmake.min.js") }}"></script>
<script src="{{ asset("plugins/pdfmake/vfs_fonts.js") }}"></script>
<script src="{{ asset("plugins/datatables-buttons/js/buttons.html5.min.js") }}"></script>
<script src="{{ asset("plugins/datatables-buttons/js/buttons.print.min.js") }}"></script>
<script src="{{ asset("plugins/datatables-buttons/js/buttons.colVis.min.js") }}"></script>
<script src="{{ asset("plugins/summernote/summernote-bs4.min.js") }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- AdminLTE App -->
<script src="{{ asset("dist/js/adminlte.min.js") }}"></script>
<!-- Page specific script -->
</body>
</html>
