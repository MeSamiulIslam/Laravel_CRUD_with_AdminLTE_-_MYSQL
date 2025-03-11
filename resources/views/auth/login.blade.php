@extends('auth.main')

@section('title') Login Page @endsection

@section('body')

<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Business Automation Ltd.</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

    @if (Session::has('error'))
        <p class="text-danger">{{ Session::get('error') }}</p>
    @endif
    @if (Session::has('success'))
        <p class="text-success">{{ Session::get('success') }}</p>
    @endif

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

      <form action="{{ route('login') }}" method="post">
        @csrf

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        @error('email')
          <p class="text-danger">{{ $message }}</p>
        @enderror

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" id="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></span>
            </div>
          </div>
        </div>
        @error('password')
          <p class="text-danger">{{ $message }}</p>
        @enderror

        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-success btn-block">Sign In</button>
          </div>
          <div class="col-4">
            <a href="{{ route('register') }}" class="btn btn-primary btn-block">Register</a>
          </div>
          <!-- /.col -->
        </div>
      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>

<script>
  document.getElementById('togglePassword').addEventListener('click', function (e) {
    // Toggle the type attribute of the password input field
    const password = document.getElementById('password');
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);

    // Toggle the icon between eye and eye-slash
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
  });
</script>

@endsection
