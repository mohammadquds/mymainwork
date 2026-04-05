<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>انتهى الاشتراك</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen font-sans">

    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-2xl border border-gray-100 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-black text-gray-900 mb-2">عفواً، انتهت صلاحية الوصول</h1>


        <div class="bg-gray-50 rounded-2xl p-4 mb-8 text-right">
            <div class="flex justify-between items-center mb-2">
                <span class="text-gray-400 text-sm">حالة الحساب:</span>
                <span class="text-red-600 font-bold text-sm">متوقف مؤقتاً</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-400 text-sm">تاريخ الانتهاء:</span>
                <span class="text-gray-800 font-mono text-sm">{{ auth()->user()->end_date?->format('Y-m-d') }}</span>
            </div>
        </div>
<a href="/homePage" class="block w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl transition-all">
 تحديث الصفحة </a>
        <div class="mt-8 pt-6 border-t border-gray-100">
            <p class="text-xs text-gray-400">إذا كنت تعتقد أن هناك خطأ، يرجى التواصل مع الدعم الفني.</p>
        </div>
    </div>

</body>
</html>
