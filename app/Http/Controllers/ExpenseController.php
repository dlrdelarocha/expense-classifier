<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseDestroyRequest;
use App\Http\Requests\ExpenseStoreRequest;
use App\Http\Requests\ExpenseUpdateRequest;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $expenses = $request->user()
            ->expenses()
            ->orderBy('spent_at', 'desc')
            ->get();

        return response()->json([
            'data' => $expenses,
            'success' => true
        ]);
    }

    public function store(ExpenseStoreRequest $request): JsonResponse
    {
        $expense = $request->user()->expenses()->create($request->validated());

        return response()->json([
            'data' => $expense,
            'success' => true,
            'message' => "Expense created successfully!"
        ]);
    }

    /**
     * @throws \Exception
     */
    public function update(ExpenseUpdateRequest $request, $expenseId): JsonResponse
    {
        $expense = Expense::where('user_id', $request->user()->id)
            ->where('id', $expenseId)
            ->first();

        if (empty($expense)) {
            throw new \Exception('Resource does not exists!', 400);
        }

        $expense->fill($request->validated());
        $expense->save();

        return response()->json([
            'data' => $expense,
            'success' => true,
            'message' => "Expense updated successfully!"
        ]);

    }

    /**
     * @throws \Exception
     */
    public function destroy(ExpenseDestroyRequest $request, $id): JsonResponse
    {
        $deletedExpense = $request->user()->expenses()
            ->where('id', $id)
            ->delete();


        if (!$deletedExpense) {
            throw new \Exception('Resource does not exists!', 400);
        }

        return response()->json([
            'success' => true,
            'message' => "Expense deleted successfully!"
        ]);
    }
}
