@extends('layouts.main')

@section('title', 'Вход')

@section('content')
    <div class="container d-flex align-items-center" style="min-height: 75vh;">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-4">
                <h1 class="text-center mb-3">{{ __('Авторизация') }}</h1>
                <section class="auth-form">
                    <form method="POST" action="{{ route('login.action') }}">
                        @csrf
                        <div class="mb-3 form-group">
                            <label for="email">{{ __('Электронная почта') }}</label>
                            <input class="form-control" id="email" name="email" value="{{ old('email') }}"
                                   placeholder="{{ __('Введите адрес электронной почты') }}">
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-group">
                            <label for="password">{{ __('Пароль') }}</label>
                            <input class="form-control" id="password" name="password"
                                   placeholder="{{ __('Введите пароль') }}">
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember"
                                   name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ __('Запомнить меня') }}</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">{{ __('Войти') }}</button>
                    </form>
                </section>
            </div>
        </div>
    </div>
@endsection
