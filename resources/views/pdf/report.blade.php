<!DOCTYPE html>
<html lang="ar" dir="rtl">
@php $arabic = new \ArPHP\I18N\Arabic(); @endphp
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* تعريف الخط ودعم الاتجاه */
        body { 
            font-family: 'DejaVu Sans', 'Times New Roman'; 
            direction: rtl; 
            text-align: right; 
            font-size: 12px; /* تصغير الخط قليلاً ليناسب Landscape */
        }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; word-wrap: break-word; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; font-style: italic; }
        
        /* تأكيد عرض الحروف المتصلة بشكل صحيح */
        .arabic-text {
            unicode-bidi: bidi-override;
            direction: rtl;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $arabic->utf8Glyphs($title ?? 'تقرير معاملات الذهب') }}</h1>
        <p> {{ now()->format('Y-m-d H:i') }}{{ $arabic->utf8Glyphs($date ?? 'تاريخ الإنشاء: ') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%">{{ $arabic->utf8Glyphs('التاريخ') }}</th>
                <th style="width: 20%">{{ $arabic->utf8Glyphs('اسم العميل') }}</th>
                <th style="width: 15%">{{ $arabic->utf8Glyphs('الهوية/الإقامة') }}</th>
                <th style="width: 7%">{{ $arabic->utf8Glyphs('عيار') }}</th>
                <th style="width: 10%">{{ $arabic->utf8Glyphs('الوزن') }}</th>
                <th style="width: 15%">{{ $arabic->utf8Glyphs('إجمالي السعر') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pdf as $item)
            <tr>
                <td>{{ $item->created_at->format('Y-m-d') }}</td>
                <td class="arabic-text">{{$arabic->utf8Glyphs($item->full_name) }}</td>
                <td>{{ $item->national_id }}</td>
                <td>{{ $item->karat }}</td>
                <td>{{ $item->weight }} g</td>
                <td>{{ number_format($item->sale_price, 2) }} SR</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p></p>
    </div>
            <p>{{ $arabic->utf8Glyphs($employee_name ?? auth()->user()->name) }} {{ $arabic->utf8Glyphs($date ?? 'تم انشاء الملف بواسطة الموظف: ') }}</p>

</body>
</html>