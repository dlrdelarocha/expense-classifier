<?php

namespace App\Services\Expense;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportExpenses
{
    protected const HEADERS_AMOUNT = 3;

    public const MAX_CHUNK = 100;

    protected static array $valueTypes = [
        'string',
        'numeric',
        'date'
    ];

    public function maxItemsAmountAllowed()
    {
        return config('import-files.expenses.max_items_amount_allowed');
    }

    public function extract($file): array
    {
        $csvData = file_get_contents($file->getRealPath());

        if ($file->getClientOriginalExtension() === 'xlsx') {
            //read xlsx
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file);

            //converts to csv
            $writer = IOFactory::createWriter($spreadsheet, 'Csv');

            $tempPath = tempnam(sys_get_temp_dir(), 'csv');
            $writer->save($tempPath);

            $csvData = file_get_contents($tempPath);
        }

        //gets exploded all rows
        $rows = str_getcsv($csvData, "\n");

        //deletes headers
        array_shift($rows);

        return $rows;
    }

    public function filter($row, $default = false): array|bool
    {
        //gets current row exploded
        $row = str_getcsv($row);

        $clearedRow = $this->clearNullValues($row);

        if (!$this->validateColumnCount($clearedRow)) {
            return $default;
        }

        if (!$this->hasValidTypes($clearedRow)) {
            return $default;
        }

        $this->formatDate($clearedRow);

        return $clearedRow;
    }

    private function clearNullValues($row): array
    {
        return array_filter($row);
    }

    public function validateColumnCount($row): bool
    {
        return count($row) === self::HEADERS_AMOUNT;
    }

    public function hasValidTypes($clearedRow): bool
    {
        foreach ($clearedRow as $key => $value) {
            $type = self::$valueTypes[$key];

            //gets current row type
            $actualType = $this->getType($value);

            if ($actualType != $type) {
                return false;
            }
        }
        return true;
    }

    private function getType($value): string
    {
        if (is_numeric($value)) {
            return 'numeric';
        } elseif (strtotime($value) !== false) {
            return 'date';
        }
        // gets current type of the value
        return gettype($value);

    }

    private function formatDate(&$clearedRow): void
    {
        //validated before
        $date = Carbon::parse($clearedRow[2]);

        $clearedRow[2] = $date->format('Y-m-d H:m:i');
    }
}