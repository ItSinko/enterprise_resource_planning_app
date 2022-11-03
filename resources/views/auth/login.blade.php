<html>

<head>
    <title>ERP</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/image/logo/logo_title.ico') }}" />
    <style>
        .login-page {
            height: 100vh;
            min-height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/adminlte/dist/css/adminlte.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/fontawesome-free/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}">
</head>

<body>
    <div class="login-page">
        <form id="login" action="/login">
            <div class="container">
                <div class="card flex-row p-5">
                    <div class="col-6 d-flex justify-content-center">
                        <div class="pe-5" style="border-right: 1px solid rgba(0, 0, 0, 0.125)">
                            <img src="{{ asset('assets/image/logo/login.png') }}" alt="logo"
                                style="max-width: 90%" />
                        </div>
                    </div>
                    <div class="col-6 d-flex align-items-center">
                        <div class="ps-5">
                            <div class="d-flex justify-content-center">
                                <img src="{{ asset('assets/image/logo/sinko.png') }}" alt="sinko"
                                    style="width: 30%" />
                            </div>
                            <div>
                                <div>
                                    <div class="input-group mb-3">
                                        <input type="text" id="username" class="form-control" placeholder="Username"
                                            autofocus />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span><i class="fas fa-envelope"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="password" id="password" class="form-control"
                                            placeholder="password" />
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span><i class="fas fa-lock"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div>
                                            <button type="submit" class="btn btn-block btn-primary login_button">
                                                <span class="fas fa-sign-in-alt"></span>
                                                Login
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</body>

</html>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script>
    function get_api(username, password) {
        $.ajax({
            type: "POST",
            url: "/api/login",
            data: {
                username: username,
                password: password
            },
            success: function(response) {
                localStorage.setItem('lokal_token', response.token);
                //console.log(sessionStorage.getItem('token'))
                //console.log(response.token);
            }
        });
    }
    $(document).on('submit', '#login', function(e) {
        e.preventDefault();
        if ($("#username").val() == "" || $("#password").val() == "") {
            swal.fire(
                'Gagal',
                'Username atau Password Kosong',
                'error'
            );
        } else {
            var username = $("#username").val();
            var password = $("#password").val();
            get_api(username, password);
            $.ajax({
                type: "POST",
                url: '{{ route('login') }}',
                data: {
                    username: username,
                    password: password,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $(".login_button").attr("disabled", true);
                    $(".login_button").text('Harap Tunggu');
                },
                success: function(response) {
                    location.reload();

                },
                error: function(xhr, status, error) {
                    swal.fire(
                        'Gagal',
                        'Username atau Password Salah',
                        'error'
                    );
                    $(".login_button").text('Login');
                    $(".login_button").attr("disabled", false);
                }
            });

        }
    });
    //      function get_api(username,password) {
    //         $.ajax({
    //         type: "POST",
    //         url: "/api/login",
    //         data: {
    //             username: username,
    //             password: password
    //         },
    //         success: function (response) {
    //             localStorage.setItem('lokal_token', response.token);
    //             //console.log(sessionStorage.getItem('token'))
    //            console.log(response.token);
    //         }
    //           });
    //      }
    //      function login() {
    //   if ($("#username").val() == "" || $("#password").val() == "") {
    //                 swal.fire(
    //                                 'Gagal',
    //                                 'Username atau Password Kosong',
    //                                 'error'
    //                             );
    //   } else {

    //     var username = $("#username").val();
    //     var password = $("#password").val();
    //     get_api(username,password);
    //     $.ajax({
    //                     type: "POST",
    //                     url: '{{ route('login') }}',
    //                     data: {
    //                         username: username,
    //                         password : password,
    //                         _token: '{{ csrf_token() }}'
    //                     },
    //                     beforeSend: function() {
    //                         $(".login_button").attr("disabled", true);
    //                         $(".login_button").text('Harap Tunggu');
    //                     },
    //                     success: function(response) {
    //                         location.reload();

    //                     },
    //                     error: function(xhr, status, error) {
    //                         swal.fire(
    //                                 'Gagal',
    //                                 'Username atau Password Salah',
    //                                 'error'
    //                             );
    //                             $(".login_button").text('Login');
    //                             $(".login_button").attr("disabled", false);
    //                     }
    //                 });
    //   }
    // }
</script>
