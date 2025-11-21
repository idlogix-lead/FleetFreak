<?php

namespace App\Exports;

use App\Models\Ledger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class DriverLedgerExport implements FromCollection, WithHeadings, WithMapping
{
    protected $ledgerEntries;

    // Accept the ledger data via constructor
    public function __construct(Collection $ledgerEntries)
    {
        $this->ledgerEntries = $ledgerEntries;
    }

    /**
    * Return the passed collection
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->ledgerEntries; // Return the data passed from the query
    }
    
    public function map($row): array
    {
        return [
            $row->driver,
            $row->customer,
            $row->trtype,
            $row->tr_date,
            $row->tr_no,
            $row->description,
            $row->debit,
            $row->credit,
        ];
    }

    public function headings(): array
    {
        return [
            'Driver',
            'Customer',
            'Transaction Type',
            'Transaction Date',
            'Transaction Number',
            'Description',
            'Trip Amount',
            'Receivable Amount',
        ];
    }
}
