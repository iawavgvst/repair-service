<?php

namespace App\Http\Controllers;

use App\Http\Services\RepairRequestService;
use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DispatcherController extends Controller
{
    /**
     * Сервис для работы с заявками
     *
     * @var RepairRequestService
     */
    protected RepairRequestService $repairRequestService;

    /**
     * Конструктор
     *
     * @param RepairRequestService $repairRequestService
     */
    public function __construct(RepairRequestService $repairRequestService)
    {
        $this->repairRequestService = $repairRequestService;
    }

    /**
     * Панель диспетчера
     *
     * @param Request $request
     * @return View
     * @throws AuthorizationException
     *
     * Метод GET: /dispatcher
     */
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $requests = $this->repairRequestService->getRequestsForDispatcher($status);
        $masters = $this->repairRequestService->getAvailableMasters();

        return view('dispatcher.index', compact('requests', 'masters', 'status'));
    }

    /**
     * Назначение мастера на заявку
     *
     * @param Request $request
     * @param RepairRequest $repairRequest
     * @return RedirectResponse
     * @throws ValidationException
     * @throws ModelNotFoundException
     *
     * Метод PATCH: /dispatcher/{repairRequest}/assign
     */
    public function assignMaster(Request $request, RepairRequest $repairRequest): RedirectResponse
    {
        $validated = $request->validate([
            'master_id' => 'required|exists:users,id',
        ], [
            'master_id.required' => 'Нужно выбрать из списка.',
        ]);

        $master = User::findOrFail($validated['master_id']);

        if (!$master->isMaster()) {
            return back()->with('error', 'Выбранный пользователь не является мастером.');
        }

        $success = $this->repairRequestService->assignMaster($repairRequest, $master);

        if ($success) {
            return back()->with('success', 'Мастер успешно назначен на заявку.');
        } else {
            return back()->with('error', 'Не удалось назначить мастера. Возможно, заявка уже обрабатывается.');
        }
    }

    /**
     * Отмена заявки
     *
     * @param RepairRequest $repairRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     *
     * Метод PATCH: /dispatcher/{repairRequest}/cancel
     */
    public function cancelRequest(RepairRequest $repairRequest): RedirectResponse
    {
        $success = $this->repairRequestService->cancelRequest($repairRequest);

        if ($success) {
            return back()->with('success', 'Заявка успешно отменена.');
        } else {
            return back()->with('error', 'Не удалось отменить заявку.');
        }
    }

    /**
     * Просмотр деталей заявки
     *
     * @param RepairRequest $repairRequest
     * @return View
     * @throws AuthorizationException
     *
     * Метод GET: /dispatcher/{repairRequest}
     */
    public function show(RepairRequest $repairRequest): View
    {
        $masters = $this->repairRequestService->getAvailableMasters();

        return view('dispatcher.show', compact('repairRequest', 'masters'));
    }
}
