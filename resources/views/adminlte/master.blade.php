<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/image/logo/logo_title.ico') }}" />
    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')
    {{-- Configured Stylesheets --}}
    {{-- <link href="{{ asset('vendor/x-editable/jquery-editable.css') }}" rel="stylesheet"/> --}}
    <link href="{{ asset('native/css/plugin.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/datatables-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/css/select2/select2-bootstrap4.min.css') }}" rel="stylesheet" />
    <link type="text/css" href="{{ asset('vendor/select2/checkbox/check.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/adminlte/dist/css/adminlte.min.css') }}" />


    {{-- <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" /> --}}

    <link rel="stylesheet" href="{{ asset('assets/fontawesome-free/css/font-awesome.6.2.0.all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/datepicker/datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-ui/jquery-ui.css') }}">

    <!-- Include Bootstrap DateTimePicker CDN -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
        rel="stylesheet">

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('master_css')
    <style>
        #user-panel-profil:hover .overlay {

            height: 30%;
            cursor: pointer;
        }

        .overlay {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 0.938);
            overflow: hidden;
            height: 0;
            transition: .5s ease;
            width: 100%;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/font.css') }}">
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" ref="https://fonts.googleapis.com/css?family=Varela+Round"> -->

    {{-- Favicon --}}
    @if (config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif
</head>

<body class="@yield('classes_body')" style="font-family:Inter; padding-top: 0;" @yield('body_data')>
    <div id="app"></div>
    {{-- Body Content --}}
    @yield('body')

    {{-- Configured Scripts --}}
    <script src="{{ asset('native/js/plugin.js') }}"></script>
    <script src="{{ asset('assets/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/select2/checkbox/check.js') }}"></script>
    <script src="{{ asset('vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('vendor/datepicker/moment.js') }}"></script>
    <script src="{{ asset('vendor/datepicker/datepicker.js') }}"></script>
    <script src="{{ asset('vendor/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/js/justgage.js') }}"></script>
    <script src="{{ asset('assets/js/raphael-2.1.4.min.js') }}"></script>
    {{-- <script src="https://unpkg.com/cropperjs"></script> --}}


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script src="{{ asset('vendor/x-editable/jquery-editable-poshytip.min.js') }}"></script> --}}
    {{-- Custom Scripts --}}
    <script src="{{ asset('native/js/login.js') }}"></script>
    <script>
        // var u = '{{ auth()->user()->username }}';
        // var p = '{{ auth()->user()->getAuthPassword() }}); ';
        // console.log(p);

        // check_token();

        // function check_token() {
        //     $.ajax({
        //         type: "POST",
        //         url: "/api/customer/data/26/0",
        //         beforeSend: function(xhr) {
        //             var access_token = localStorage.getItem('lokal_token');
        //             xhr.setRequestHeader('Authorization', 'Bearer ' + access_token);
        //         },
        //         success: function(response) {
        //             console.log(response.status);
        //             if (response.status == 'Token expired' || response.status == 'Token invalid') {
        //                 get_api(u, p);
        //             }
        //         }
        //     });
        // }


        // function get_api(username, password) {
        //     $.ajax({
        //         type: "POST",
        //         url: "/api/login",
        //         data: {
        //             username: username,
        //             password: 12345678
        //         },
        //         success: function(response) {
        //             localStorage.setItem('lokal_token', response.token);
        //             //console.log(sessionStorage.getItem('token'))
        //             console.log(response.token);
        //         }
        //     });
        // }


        // $(document).ready(function() {

        //     var $modal = $('#profileImageModal');

        //     var image = document.getElementById('sample_image');

        //     var cropper;

        //     $('#upload_image').change(function(event) {
        //         var files = event.target.files;

        //         var done = function(url) {
        //             image.src = url;
        //             $modal.modal('show');
        //         };

        //         if (files && files.length > 0) {
        //             reader = new FileReader();
        //             reader.onload = function(event) {
        //                 done(reader.result);
        //             };
        //             reader.readAsDataURL(files[0]);
        //         }
        //     });

        //     $modal.on('shown.bs.modal', function() {
        //         cropper = new Cropper(image, {
        //             aspectRatio: 1 / 1,

        //             dragMode: 'move',
        //             preview: 'preview',
        //             viewMode: 1,
        //         });
        //     }).on('hidden.bs.modal', function() {
        //         cropper.destroy();
        //         cropper = null;
        //     });

        //     $('#update_photo_profile').click(function() {
        //         canvas = cropper.getCroppedCanvas({
        //             width: 542,
        //             height: 560
        //         });

        //         canvas.toBlob(function(blob) {
        //             url = URL.createObjectURL(blob);
        //             var reader = new FileReader();
        //             reader.readAsDataURL(blob);
        //             reader.onloadend = function() {
        //                 var base64data = reader.result;
        //                 $.ajaxSetup({
        //                     headers: {
        //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
        //                             'content')
        //                     }
        //                 });
        //                 $.ajax({
        //                     url: '/edit_profile_photo',
        //                     method: 'POST',
        //                     data: {
        //                         image: base64data
        //                     },
        //                     success: function(data) {

        //                         $modal.modal('hide');
        //                         $('#uploaded_image').attr('src', data);
        //                         if (data.data == 'success') {
        //                             Swal.fire({
        //                                 title: 'Berhasil',
        //                                 text: 'Foto user berhasil di ubah',
        //                                 icon: 'success',
        //                                 showCancelButton: false,
        //                                 confirmButtonColor: '#3085d6',
        //                                 cancelButtonColor: '#d33',
        //                                 confirmButtonText: 'OK'
        //                             }).then((result) => {
        //                                 if (result.isConfirmed) {
        //                                     event.preventDefault();
        //                                     window.location.reload();
        //                                 }
        //                             })
        //                         } else {
        //                             Swal.fire({
        //                                 icon: 'error',
        //                                 title: 'Oops...',
        //                                 text: 'Gagal Mengubah Foto',
        //                             })
        //                         }


        //                     }
        //                 });
        //             };
        //         });
        //     });

        // });
    </script>
    @yield('master_js')
</body>

</html>
