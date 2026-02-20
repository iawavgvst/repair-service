@extends('layouts.main')

@section('title', 'Заявка #' . $repairRequest->id)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('Заявка #') }}{{ $repairRequest->id }}</h4>
                    <div>
                        <a href="{{ route('master.index') }}"
                           class="btn btn-outline-secondary btn-sm">{{ __('Вернуться к списку') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Информация о клиенте') }}</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>{{ __('ФИО') }}:</strong> {{ $repairRequest->client_name }}</p>
                                    <p><strong>{{ __('Номер телефона') }}:</strong> {{ $repairRequest->phone }}</p>
                                    <p><strong>{{ __('Адрес') }}:</strong> {{ $repairRequest->address }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ __('Информация о заявке') }}</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>{{ __('Статус') }}:</strong>
                                        <span
                                            class="badge bg-{{ $repairRequest->status === 'assigned' ? 'warning' : ($repairRequest->status === 'in_progress' ? 'primary' : 'success') }}">
                                        {{ __('status.' . $repairRequest->status) }}
                                    </span>
                                    </p>
                                    <p><strong>{{ __('Создана') }}:
                                        </strong> {{ $repairRequest->created_at->format('d.m.Y H:i') }}</p>
                                    <p><strong>{{ __('Обновлена') }}:
                                        </strong> {{ $repairRequest->updated_at->format('d.m.Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Описание проблемы') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="border rounded p-3 bg-light">
                                {{ $repairRequest->problem_text }}
                            </div>
                        </div>
                    </div>
                    @if($repairRequest->status === 'assigned' || $repairRequest->status === 'in_progress')
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Действия') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if($repairRequest->status === 'assigned')
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6>{{ __('Взять в работу?') }}</h6>
                                                    <p class="text-muted">{{ __('Заявка перейдет в статус "in_progress".') }}</p>
                                                    <form action="{{ route('master.take', $repairRequest) }}"
                                                          method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-primary"
                                                                onclick="return confirm('Взять заявку в работу?')">
                                                            {{ __('Взять в работу') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($repairRequest->status === 'in_progress')
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6>{{ __('Завершить заявку') }}</h6>
                                                    <p class="text-muted">{{ __('Заявка перейдет в статус "done"') }}</p>
                                                    <form action="{{ route('master.complete', $repairRequest) }}"
                                                          method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success"
                                                                onclick="return confirm('Завершить заявку?')">
                                                            {{ __('Завершить') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
