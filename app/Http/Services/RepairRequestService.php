<?php

namespace App\Http\Services;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Database\Eloquent\Collection;

class RepairRequestService
{
    /**
     * Создание новой заявки
     *
     * @param array $data
     * @return RepairRequest
     * @throws QueryException
     */
    public function createRequest(array $data): RepairRequest
    {
        return RepairRequest::create([
            'client_name' => $data['client_name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'problem_text' => $data['problem_text'],
            'status' => 'new',
        ]);
    }

    /**
     * Назначение мастера на заявку
     *
     * @param RepairRequest $request
     * @param User $master
     * @return bool
     * @throws QueryException
     */
    public function assignMaster(RepairRequest $request, User $master): bool
    {
        if (!$request->canBeAssigned()) {
            return false;
        }

        $request->update([
            'status' => 'assigned',
            'assigned_to' => $master->id,
        ]);

        return true;
    }

    /**
     * Отмена заявки
     *
     * @param RepairRequest $request
     * @return bool
     * @throws QueryException
     */
    public function cancelRequest(RepairRequest $request): bool
    {
        if (!$request->canBeCanceled()) {
            return false;
        }

        $request->update([
            'status' => 'canceled',
        ]);

        return true;
    }

    /**
     * Принятие заявки в работу
     *
     * @param RepairRequest $request
     * @param User $master
     * @return array
     * @throws Throwable
     */
    public function takeRequest(RepairRequest $request, User $master): array
    {
        if ($request->assigned_to !== $master->id) {
            return [
                'success' => false,
                'message' => 'Заявка не назначена вам.',
            ];
        }

        if (!$request->canBeTaken()) {
            return [
                'success' => false,
                'message' => 'Заявка не может быть взята в работу.',
            ];
        }

        return DB::transaction(function () use ($request) {
            $lockedRequest = RepairRequest::where('id', $request->id)
                ->where('status', 'assigned')
                ->lockForUpdate()
                ->first();

            if (!$lockedRequest) {
                return [
                    'success' => false,
                    'message' => 'Заявка уже взята другим мастером или изменила статус.',
                ];
            }

            $lockedRequest->update([
                'status' => 'in_progress',
            ]);

            return [
                'success' => true,
                'message' => 'Заявка взята в работу.',
            ];
        });
    }

    /**
     * Завершение заявки
     *
     * @param RepairRequest $request
     * @param User $master
     * @return bool
     * @throws QueryException
     */
    public function completeRequest(RepairRequest $request, User $master): bool
    {
        if ($request->assigned_to !== $master->id || !$request->canBeCompleted()) {
            return false;
        }

        $request->update([
            'status' => 'done',
        ]);

        return true;
    }

    /**
     * Получение заявки для диспетчера
     *
     * @param string|null $status
     * @return Collection
     */
    public function getRequestsForDispatcher(?string $status = null): Collection
    {
        $query = RepairRequest::with('assignedMaster')
            ->orderBy('id', 'asc');

        if ($status && in_array($status, ['new', 'assigned', 'in_progress', 'done', 'canceled'])) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Получение заявки для мастера
     *
     * @param User $master
     * @param string|null $status
     * @return Collection
     */
    public function getRequestsForMaster(User $master, ?string $status = null): Collection
    {
        $query = $master->assignedRequests()
            ->orderBy('id', 'asc');

        if ($status && in_array($status, ['assigned', 'in_progress', 'done'])) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Получение доступных мастеров
     *
     * @return Collection
     */
    public function getAvailableMasters(): Collection
    {
        return User::where('role', 'master')->get();
    }
}
