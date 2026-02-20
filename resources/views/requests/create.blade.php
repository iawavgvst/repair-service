@extends('layouts.main')

@section('title', 'Создание заявки')

@section('content')
    <div class="container d-flex align-items-center" style="min-height: 75vh;">
        <div class="row justify-content-center w-100">
            <div class="col-md-6 col-lg-4">
                <h1 class="text-center mb-3">{{ __('Форма создания заявки') }}</h1>
                <section class="creation-request-form">
                    <form method="POST" action="{{ route('requests.store') }}">
                        @csrf
                        <div class="mb-3 form-group">
                            <label for="client_name">{{ __('ФИО') }} *</label>
                            <input type="text" class="form-control" id="client_name" name="client_name"
                                   value="{{ old('client_name') }}" placeholder="{{ __('Введите ФИО') }}">
                            @error('client_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-group">
                            <label for="phone">{{ __('Номер телефона') }} *</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="{{ old('phone') }}" placeholder="{{ __('Укажите номер телефона') }}">
                            @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-group">
                            <label for="address">{{ __('Адрес') }} *</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   value="{{ old('address') }}" placeholder="{{ __('Введите адрес') }}">
                            @error('address')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-group">
                            <label for="problem_text">{{ __('Описание проблемы') }} *</label>
                            <textarea class="form-control" id="problem_text" name="problem_text" rows="4"
                                      placeholder="{{ __('Опишите Вашу проблему') }}">{{ old('problem_text') }}</textarea>
                            @error('problem_text')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('Создать заявку') }}</button>
                            <a href="{{ url('/') }}" class="btn btn-secondary">{{ __('На главную') }}</a>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
@endsection
