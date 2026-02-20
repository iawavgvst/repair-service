<?php

namespace App\Http\Controllers;

use App\Http\Services\RepairRequestService;
use App\Models\RepairRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Throwable;

class MasterController extends Controller
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
     * Панель мастера
     *
     * @param Request $request
     * @return View
     * @throws AuthorizationException
     *
     * Метод GET: /master
     */
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $requests = $this->repairRequestService->getRequestsForMaster(Auth::user(), $status);

        return view('master.index', compact('requests', 'status'));
    }

    /**
     * Взятие заявки в работу
     *
     * @param RepairRequest $repairRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws Throwable
     *
     * Метод GET: /master/{repairRequest}/take
     */
    public function takeRequest(RepairRequest $repairRequest): RedirectResponse
    {
        $result = $this->repairRequestService->takeRequest($repairRequest, Auth::user());

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Завершение заявки
     *
     * @param RepairRequest $repairRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     *
     * Метод PATCH: /master/{repairRequest}/complete
     */
    public function completeRequest(RepairRequest $repairRequest): RedirectResponse
    {
        $success = $this->repairRequestService->completeRequest($repairRequest, Auth::user());

        if ($success) {
            return back()->with('success', 'Заявка успешно завершена.');
        } else {
            return back()->with('error', 'Не удалось завершить заявку.');
        }
    }

    /**
     * Просмотр деталей заявки
     *
     * @param RepairRequest $repairRequest
     * @return View
     * @throws AuthorizationException
     *
     * Метод GET: /master/{repairRequest}
     */
    public function show(RepairRequest $repairRequest): View
    {
        if ($repairRequest->assigned_to !== Auth::id()) {
            abort(403, 'У вас нет доступа к этой заявке.');
        }

        return view('master.show', compact('repairRequest'));
    }
}
