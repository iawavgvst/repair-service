@extends('layouts.main')

@section('title', 'Панель диспетчера')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h1>{{ __('Панель диспетчера') }}</h1>
                <p class="text-muted">{{ __('Управление заявками на ремонт') }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('requests.create') }}" class="btn btn-success">{{ __('Создать новую заявку') }}</a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">{{ __('Фильтр заявок') }}</h4>
                <div class="btn-group" role="group">
                    <a href="{{ route('dispatcher.index') }}"
                       class="btn btn-outline-secondary {{ !$status ? 'active' : '' }}">{{ __('Все') }}</a>
                    <a href="{{ route('dispatcher.index', ['status' => 'new']) }}"
                       class="btn btn-outline-info {{ $status === 'new' ? 'active' : '' }}">{{ __('Новые') }}</a>
                    <a href="{{ route('dispatcher.index', ['status' => 'assigned']) }}"
                       class="btn btn-outline-warning {{ $status === 'assigned' ? 'active' : '' }}">{{ __('Назначенные') }}</a>
                    <a href="{{ route('dispatcher.index', ['status' => 'in_progress']) }}"
                       class="btn btn-outline-primary {{ $status === 'in_progress' ? 'active' : '' }}">{{ __('В работе') }}</a>
                    <a href="{{ route('dispatcher.index', ['status' => 'done']) }}"
                       class="btn btn-outline-success {{ $status === 'done' ? 'active' : '' }}">{{ __('Завершенные') }}</a>
                    <a href="{{ route('dispatcher.index', ['status' => 'canceled']) }}"
                       class="btn btn-outline-danger {{ $status === 'canceled' ? 'active' : '' }}">{{ __('Отмененные') }}</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if($requests->isEmpty())
                    <div class="text-center py-5">
                        <h5>{{ __('Заявок не найдено') }}</h5>
                        <p class="text-muted">{{ __('Создайте первую заявку или измените фильтр') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Клиент') }}</th>
                                <th>{{ __('Номер телефона') }}</th>
                                <th>{{ __('Адрес') }}</th>
                                <th>{{ __('Статус') }}</th>
                                <th>{{ __('Создана') }}</th>
                                <th>{{ __('Действия') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($requests as $repairRequest)
                                <tr>
                                    <td>{{ $repairRequest->id }}</td>
                                    <td>{{ $repairRequest->client_name }}</td>
                                    <td>{{ $repairRequest->phone }}</td>
                                    <td>{{ Str::limit($repairRequest->address, 30) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $repairRequest->status === 'new' ? 'info' : ($repairRequest->status === 'assigned' ? 'warning' : ($repairRequest->status === 'in_progress' ? 'primary' : ($repairRequest->status === 'done' ? 'success' : 'danger'))) }}">
                                            {{ __('status.' . $repairRequest->status) }}</span>
                                    </td>
                                    <td>{{ $repairRequest->assignedMaster->name ?? 'Не назначен' }}</td>
                                    <td>{{ $repairRequest->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('dispatcher.show', $repairRequest) }}"
                                               class="btn btn-outline-info">{{ __('Просмотр') }}</a>
                                            @if($repairRequest->status === 'new')
                                                <button type="button" class="btn btn-outline-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#assignModal{{ $repairRequest->id }}">
                                                    {{ __('Назначить') }}
                                                </button>
                                                <form action="{{ route('dispatcher.cancel', $repairRequest) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-danger"
                                                            onclick="return confirm('Вы уверены, что хотите отменить заявку?')">
                                                        {{ __('Отменить') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @if($repairRequest->status === 'new')
                                    <div class="modal fade" id="assignModal{{ $repairRequest->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('Назначить мастера на заявку #') }}{{ $repairRequest->id }}</h5>
                                                    <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('dispatcher.assign', $repairRequest) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="master_id">{{ __('Выберите мастера') }}:</label>
                                                            <select class="form-select" id="master_id" name="master_id">
                                                                <option
                                                                    value="">{{ __('Выберите мастера...') }}</option>
                                                                @foreach($masters as $master)
                                                                    <option
                                                                        value="{{ $master->id }}">{{ $master->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('master_id')
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <p><strong>{{ __('Клиент') }}
                                                                :</strong> {{ $repairRequest->client_name }}</p>
                                                        <p><strong>{{ __('Адрес') }}
                                                                :</strong> {{ $repairRequest->address }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">{{ __('Отмена') }}</button>
                                                        <button type="submit"
                                                                class="btn btn-primary">{{ __('Назначить') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
