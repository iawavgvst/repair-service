<?php

namespace App\Http\Controllers;

use App\Http\Requests\RepairRequest\StoreRequest;
use Illuminate\View\View;
use App\Http\Services\RepairRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Exception;

class RepairRequestController extends Controller
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
     * Форма создания заявки
     *
     * @return View
     *
     * Метод GET: /requests/create
     */
    public function create(): View
    {
        return view('requests.create');
    }

    /**
     * Создание новой заявки
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     * @throws Exception
     *
     * Метод POST: /requests
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        try {
            $repairRequest = $this->repairRequestService->createRequest($data);

            return redirect()->route('requests.create')
                ->with('success', "Заявка успешно создана! Номер заявки: $repairRequest->id.");
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при создании заявки: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
