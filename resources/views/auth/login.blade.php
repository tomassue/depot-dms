<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="The DEPOT Document Management System is developed to assist the City Equipment Depot Office in managing requests such as vehicle repair and aircon cleaning/repair. Additionally, this system helps the office to track outgoing documents.">
    <meta name="keywords" content="DEPOT, Cagayan de Oro, RISE">
    <meta name="author" content="City Management Information Systems and Database Management">
    <META NAME="robots" CONTENT="noindex,nofollow">

    <link rel="icon" href="{{ asset('assets/images/cdo-seal.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/cdo-seal.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }
    </style>
    <title>DEPOT DMS</title>
</head>

<body>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="row col-md-8 col-lg-7 col-xl-6">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/login-image.png') }}" class="img-fluid" alt="cdo-logo" width="1000px">
                    </div>
                </div>

                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/login-image-2.png') }}" class="img-fluid" alt="cdo-logo" width="600px">
                    </div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif
                        <div class="mb-3">
                            <label for="inputUsername" class="form-label">Username</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn mb-lg-5" style="background-color: #66ABAC; color: #eee;">LOGIN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>