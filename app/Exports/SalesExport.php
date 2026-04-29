<?php

namespace App\Exports;

use App\Models\form;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths; // لإعطاء مساحة للصورة
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesExport implements FromQuery, WithHeadings, WithMapping, WithEvents, WithDrawings, WithColumnWidths
{
    protected $startDate;
    protected $endDate;
    protected $allowedIds;
    protected $arabic;

    public function __construct($startDate, $endDate, $allowedIds)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->allowedIds = $allowedIds;
        $this->arabic = new \ArPHP\I18N\Arabic();
    }

    // 1. جلب البيانات من القاعدة مباشرة (أفضل للأداء)
    public function query()
    {
        return form::query()
            ->whereIn('user_id', $this->allowedIds)
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->orderBy('created_at', 'asc');
    }

    // 2. وضع الصور في العمود I
    public function drawings()
    {
        $drawings = [];
        // نجلب البيانات فعلياً هنا لنعرف مكان الصور
        $sales = $this->query()->get();

        foreach ($sales as $index => $sale) {
            if ($sale->product_image && file_exists(public_path('storage/' . $sale->product_image))) {
                $drawing = new Drawing();
                $drawing->setName('صورة المنتج');
                $drawing->setPath(public_path('storage/' . $sale->product_image));
                $drawing->setHeight(70); 
                $drawing->setOffsetX(5); // إزاحة خفيفة لتتوسط الخلية
                $drawing->setOffsetY(5);
                $drawing->setCoordinates('I' . ($index + 2)); 
                $drawings[] = $drawing;
            }
        }
        return $drawings;
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
            'الإجمالي (ريال)',
            'صورة المنتج' ,// العمود I
            'نوع الوحدة', // العمود J
            'الوصف' // العمود K
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->created_at->format('Y-m-d H:i'),
            $sale->invoice_number,
            $sale->full_name,
            " " . $sale->national_id,
            $sale->weight,
            $sale->karat,
            $sale->sale_price,
            $sale->weight * $sale->sale_price, 
            '', // نترك مكان الصورة فارغاً لأن الـ Drawings ستوضع فوقه
            $sale->unit_type ??$this->arabic->utf8Glyphs('لا يوجد وحدة') ,
            $sale->description ??$this->arabic->utf8Glyphs('لا يوجد وصف') ,
        ];
    }

    // تحديد عرض الأعمدة (مهم جداً للصور)
    public function columnWidths(): array
    {
        return [
            'A' => 20, // التاريخ
            'I' => 25, // عرض كافٍ للصورة
            'C' => 25, // اسم العميل
            'K' => 50, // الوصف
            'J' => 20, // نوع الوحدة
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->setRightToLeft(true);
                
                // تكبير ارتفاع الصفوف لتناسب الصور
                $highestRow = $sheet->getHighestRow();
                for ($i = 2; $i <= $highestRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(60); 
                }

                // تنسيق العناوين (Bold + Color)
                $sheet->getStyle('A1:I1')->getFont()->setBold(true);
            },
        ];
    }
}