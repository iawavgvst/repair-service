@extends('layouts.main')

@section('title', 'Заявка #' . $repairRequest->id)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('Заявка #') }}{{ $repairRequest->id }}</h4>
                    <div>
                        <a href="{{ route('dispatcher.index') }}"
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
                                            class="badge bg-{{ $repairRequest->status === 'new' ? 'info' : ($repairRequest->status === 'assigned' ? 'warning' : ($repairRequest->status === 'in_progress' ? 'primary' : ($repairRequest->status === 'done' ? 'success' : 'danger'))) }}">
                                        {{ __('status.' . $repairRequest->status) }}
                                    </span>
                                    </p>
                                    <p><strong>{{ __('Создана') }}:
                                        </strong> {{ $repairRequest->created_at->format('d.m.Y H:i') }}</p>
                                    <p><strong>{{ __('Обновлена') }}:
                                        </strong> {{ $repairRequest->updated_at->format('d.m.Y H:i') }}</p>
                                    @if($repairRequest->assignedMaster)
                                        <p><strong> {{ __('Назначенный мастер') }}:
                                            </strong> {{ $repairRequest->assignedMaster->name }}</p>
                                    @endif
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
                    @if($repairRequest->status === 'new')
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">{{ __('Действия') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>{{ __('Назначить мастера?') }}</h6>
                                                <form action="{{ route('dispatcher.assign', $repairRequest) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="mb-3">
                                                        <label for="master_id">{{ __('Выберите мастера') }}:</label>
                                                        <select class="form-select" id="master_id" name="master_id">
                                                            <option value="">{{ __('Выберите мастера...') }}</option>
                                                            @foreach($masters as $master)
                                                                <option
                                                                    value="{{ $master->id }}">{{ $master->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('master_id')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <button type="submit"
                                                            class="btn btn-primary">{{ __('Назначить') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>{{ __('Отменить заявку') }}</h6>
                                                <p class="text-muted">{{ __('Заявка будет отменена и перейдет в статус "canceled".') }}</p>
                                                <form action="{{ route('dispatcher.cancel', $repairRequest) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Вы уверены, что хотите отменить заявку?')">
                                                        {{ __('Отменить заявку') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
