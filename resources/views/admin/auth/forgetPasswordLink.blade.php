<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mango-Byte 3 | Forgot Password</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../index2.html" class="h1"><b>Mango </b>Byte</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
      <form action="{{ route('reset.password.post') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group row">
            <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
            <div class="col-md-6">
                <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
            <div class="col-md-6">
                <input type="password" id="password" class="form-control" name="password" required autofocus>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
            <div class="col-md-6">
                <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>
                @if ($errors->has('password_confirmation'))
                    <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>
        </div>

        <div class="col-md-6 offset-md-4">
            <button type="submit" class="btn btn-primary">
                Reset Password
            </button>
        </div>
    </form>

      {{-- <p class="mt-3 mb-1">
        <a href="{{ route('dashboard') }}">Login</a>
      </p> --}}
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
</body>
</html>
