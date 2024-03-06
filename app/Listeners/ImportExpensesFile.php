<?php

namespace App\Listeners;

use App\Mail\NotifyExpensesFileUploaded;
use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use App\Services\Expense\ImportExpenses;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ImportExpensesFile  implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct(public ImportExpenses $import)
    {}

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = User::findOrFail($event->userId);

        $successImportCount = 0;
        collect($event->fileRows)->chunk(ImportExpenses::MAX_CHUNK)->map(function ($expenses) use ($user, &$successImportCount) {

            $newExpenses = $expenses->map(function ($expense) use ($user) {
                // Filters only valid rows; if a row is invalid, it is skipped.
                $cleanedRow = $this->import->filter($expense);
                if (empty($cleanedRow)) {
                    return $cleanedRow;
                }

                return $this->newExpense($cleanedRow, $user->id);
            })->filter();

            //Imported successfully
            $successImportCount = $successImportCount + $newExpenses->count();

            Expense::query()->insert($newExpenses->toArray());
        });

        $failedImportCount =  count($event->fileRows) - $successImportCount;


        Mail::to($user->email)->send(new NotifyExpensesFileUploaded($successImportCount, $failedImportCount));

    }

    //@todo change to model
    private function newExpense(array $expenseValues, $userId): array
    {
        //creates a new array ['name' => 'foo, 'amount' => '100', 'created_at' => 'Y-m-d']
        $keys = ['name', 'amount', 'spent_at'];
        $expense = array_combine($keys, $expenseValues);

        //adds a default category
        $expense['user_id'] = $userId;
        $expense['category_id'] = Category::first()->id;
        $expense['created_at'] = now();

        return $expense;
    }
}
