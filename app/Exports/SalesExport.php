<?php

namespace App\Exports;

use App\Models\form;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesExport implements FromQuery, WithHeadings, WithMapping, WithEvents
{
    protected $startDate;
    protected $endDate;

    // Receive the dates from Livewire
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    //  search the data based on dates
    public function query()
    {
        $query = form::query();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->orderBy('created_at', 'asc');
    }

    public function headings(): array
    {
        return [
            'التاريخ',
            'رقم الفاتورة',
            'اسم العميل',
            'الهوية',
            'الوزن (جرام)',
            'العيار',
            'سعر الجرام',
            'الإجمالي (ريال)'
        ];
    }

    //  make the database rows to the columns
    public function map($sale): array
    {
        return [
            $sale->created_at->format('Y-m-d H:i'),
            $sale->id,
            $sale->full_name,
            ' ' . $sale->national_id,
            $sale->weight,
            $sale->karat,
            $sale->sale_price,
            $sale->weight * $sale->sale_price,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }
}
