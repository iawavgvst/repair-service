@extends('layouts.main')

@section('title', 'Панель мастера')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h1>{{ __('Панель мастера') }}</h1>
                <p class="text-muted">{{ __('Ваши заявки на ремонт') }}</p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">{{ __('Фильтр заявок') }}</h4>
                <div class="btn-group" role="group">
                    <a href="{{ route('master.index') }}"
                       class="btn btn-outline-secondary {{ !$status ? 'active' : '' }}">Все</a>
                    <a href="{{ route('master.index', ['status' => 'assigned']) }}"
                       class="btn btn-outline-warning {{ $status === 'assigned' ? 'active' : '' }}">Назначенные</a>
                    <a href="{{ route('master.index', ['status' => 'in_progress']) }}"
                       class="btn btn-outline-primary {{ $status === 'in_progress' ? 'active' : '' }}">В работе</a>
                    <a href="{{ route('master.index', ['status' => 'done']) }}"
                       class="btn btn-outline-success {{ $status === 'done' ? 'active' : '' }}">Завершенные</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if($requests->isEmpty())
                    <div class="text-center py-5">
                        <h5>{{ __('Заявок не найдено') }}</h5>
                        <p class="text-muted">{{ __('Вам пока не назначили заявок') }}</p>
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
                                            class="badge bg-{{ $repairRequest->status === 'assigned' ? 'warning' : ($repairRequest->status === 'in_progress' ? 'primary' : 'success') }}">
                                            {{ __('status.' . $repairRequest->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $repairRequest->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('master.show', $repairRequest) }}"
                                               class="btn btn-outline-info">{{ __('Просмотр') }}</a>

                                            @if($repairRequest->status === 'assigned')
                                                <form action="{{ route('master.take', $repairRequest) }}" method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-primary"
                                                            onclick="return confirm('Взять заявку в работу?')">
                                                        {{ __('Взять в работу') }}
                                                    </button>
                                                </form>
                                            @endif

                                            @if($repairRequest->status === 'in_progress')
                                                <form action="{{ route('master.complete', $repairRequest) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success"
                                                            onclick="return confirm('Завершить заявку?')">
                                                        {{ __('Завершить') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
