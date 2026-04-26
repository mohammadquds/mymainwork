<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $arabic->utf8Glyphs('invoice') }} #{{ $sale->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; direction: rtl; text-align: right; }
        .header { text-align: center; margin-bottom: 30px; }
        .details { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .details th, .details td { border: 1px solid #ddd; padding: 10px; }
        .total { font-size: 24px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        {{-- <h1>{{ $arabic->utf8Glyphs('تقرير ') }}</h1> --}}
        <p> <strong>{{ $sale->invoice_number}}  </strong>   {{ $arabic->utf8Glyphs('رقم الفاتورة:') }} </p>
        <p> <strong> {{ $sale->created_at->format('Y-m-d') }} </strong>  {{ $arabic->utf8Glyphs('التاريخ:') }} </p>
    </div>

    <table class="details">
        <tr>
            <th> {{ $sale->full_name }} </th>
            <td>{{ $arabic->utf8Glyphs('اسم العميل') }}</td>
        </tr>
        <tr>
            <th>{{ $sale->national_id }}</th>
            <td> {{ $arabic->utf8Glyphs('رقم الهوية') }} </td>
        </tr>
        <tr>
            <th> {{ $sale->karat }}K</th>
            <td> {{ $arabic->utf8Glyphs('العيار') }}</td>
        </tr>
        <tr>
            <th> {{ $arabic->utf8Glyphs('جرام') }} {{ $sale->weight }} </th>
            <td> {{ $arabic->utf8Glyphs('الوزن') }} </td>
        </tr>
        <tr>
            <th> {{ $arabic->utf8Glyphs('ريال') }}  {{ number_format($sale->sale_price, 2) }} </th>
            <td> {{ $arabic->utf8Glyphs('سعر الجرام') }}</td>
        </tr>
    </table>

    <div class="total">
     {{ $arabic->utf8Glyphs('ريال') }}   {{ number_format($sale->weight * $sale->sale_price, 2) }}   {{ $arabic->utf8Glyphs('الإجمالي:') }}
    </div>

    <p style="margin-top: 50px;"> <strong>  {{ $sale->employee_name }} </strong> {{ $arabic->utf8Glyphs('    الموظف:') }} </p>
    {{-- <p><strong> {{ $sale->store_name }} </strong> {{ $arabic->utf8Glyphs('اسم المحل:') }} </p> --}}

</body>
</html>
