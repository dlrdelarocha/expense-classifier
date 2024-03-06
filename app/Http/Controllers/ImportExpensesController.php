<?php

namespace App\Http\Controllers;

use App\Events\FileAmountItemsExceeded;
use App\Mail\NotifyExpensesFileUploaded;
use App\Models\Category;
use App\Models\Expense;
use App\Services\Expense\ImportExpenses;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ImportExpensesController extends Controller
{
    public function upload(Request $request, ImportExpenses $import): JsonResponse
    {
        $file = $request->file;

        $rows = $import->extract($file);

        if(count($rows) >= $import->maxItemsAmountAllowed()) {

            event(new FileAmountItemsExceeded($rows, $request->user()->id));

            return response()->json([
                'success' => true,
                'message' => "File uploaded. Once we have imported your data,we will send you an email with the import information."
            ]);

        }

        //@todo could be included in a trait
        $successImportCount = 0;
        collect($rows)->chunk(ImportExpenses::MAX_CHUNK)->map(function ($expenses) use ($import, &$successImportCount) {
            $newExpenses = $expenses->map(function ($expense) use ($import) {
                // Filters only valid rows; if a row is invalid, it is skipped.
                $cleanedRow = $import->filter($expense);
                if (empty($cleanedRow)) {
                    return $cleanedRow;
                }

                return $this->newExpense($cleanedRow);
            })->filter();

            //Imported successfully
            $successImportCount = $successImportCount + $newExpenses->count();

            Expense::query()->insert($newExpenses->toArray());
        });

        $failedImportCount =  count($rows) - $successImportCount;

        Mail::to($request->user()->email)->send(new NotifyExpensesFileUploaded($successImportCount, $failedImportCount));

        return response()->json([
            'success' => true,
            'message' => 'File imported successfully, we have sent a report to your email!'
        ]);
    }

    private function newExpense(array $expenseValues): array
    {
        //creates a new array ['name' => 'foo, 'amount' => '100', 'created_at' => 'Y-m-d']
        $keys = ['name', 'amount', 'spent_at'];
        $expense = array_combine($keys, $expenseValues);

        //adds a default category
        $expense['user_id'] = auth()->id();
        $expense['category_id'] = Category::first()->id;
        $expense['created_at'] = now();

        return $expense;
    }
}
