@extends('tenant.layouts.auth')

@section('content')
<section class="body-sign">
    <div class="center-sign">
        <div class="card center-logo">
            <img src="{{ asset('logo.png')}}" alt="Logo" class="img-fluid center-block">
        </div>
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group my-3">
                        <label>Correo electrónico</label>
                        <div class="input-group">
                            <input id="email" type="email" name="email" class="form-control form-control"
                                value="{{ old('email') }}">
                            <span class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </span>
                        </div>
                        @if ($errors->has('email'))
                        <label class="error">
                            <strong>{{ $errors->first('email') }}</strong>
                        </label>
                        @endif
                    </div>
                    <div class="form-group mb-4 {{ $errors->has('password') ? ' error' : '' }}">
                        <label>Contraseña</label>
                        <div class="input-group">
                            <input name="password" type="password" class="form-control form-control">
                            <span class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </span>
                        </div>
                        @if ($errors->has('password'))
                        <label class="error">
                            <strong>{{ $errors->first('password') }}</strong>
                        </label>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="checkbox-custom checkbox-default">
                                <input name="remember" id="RememberMe" type="checkbox" {{ old('remember') ? 'checked'
                                    : '' }}>
                                <label for="RememberMe">Recordarme</label>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Iniciar sesión</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
