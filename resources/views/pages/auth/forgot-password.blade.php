<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- هذا السطر هو المسؤول عن تغيير الاسم من Laravel إلى Gold System --}}
    <title>Gold System | نسيت كلمة المرور</title>

    {{-- الأيقونة --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('GoldSystem.ico') }}">
    <link rel="shortcut icon" href="{{ asset('GoldSystem.ico') }}?v=2">

    {{--  tailwind التنسيق --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 p-4">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold">
                {{ __('نسيت كلمه المرور؟') }}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('لا يوجد مشكلة. فقط أخبرنا بعنوان بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only text-sm font-medium text-gray-700">{{ __('عنوان البريد الإلكتروني') }}</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                        placeholder="{{ __(' البريد الإلكتروني') }}">
                    @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    {{ __('إرسال رابط إعادة تعيين كلمة المرور') }}
                </button>
            </div>
        </form>

        <div class="text-center text-sm text-gray-600">
            {{ __('أوه!,تتذكر كلمة المرور؟') }}
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                {{ __('تسجيل الدخول') }}
            </a>
        </div>
    </div>
</body>
</html>