<div class="bg-white shadow sm:rounded-lg p-8 border border-gray-200" dir="rtl">

    {{-- Top Section: Title & Description --}}
    <div class="mb-6 w-full text-right">
        <h2 class="text-2xl font-bold text-gray-900 mb-2 text-right">جلسات المتصفح</h2>
        <p class="text-sm text-gray-500 text-right whitespace-normal leading-relaxed">
            إذا لزم الأمر، يمكنك تسجيل الخروج من جميع جلسات المتصفح الأخرى عبر جميع أجهزتك. بعض الجلسات الحديثة مدرجة أدناه. إذا كنت تشعر أن حسابك قد تم اختراقه، يجب عليك أيضاً تغيير كلمة المرور الخاصة بك.
        </p>
    </div>

    {{-- The List of Active Sessions --}}
    @if (count($this->sessions) > 0)
        <div class="mt-4 space-y-4 w-full">
            @foreach ($this->sessions as $session)
                <div class="flex flex-row items-center justify-start gap-4 bg-gray-50 p-4 rounded-lg border border-gray-100 w-full">

                    {{-- Icon: Desktop or Mobile --}}
                    <div class="flex-shrink-0">
                        @if (str_contains($session->agent, 'iOS') || str_contains($session->agent, 'Android'))
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        @else
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        @endif
                    </div>

                    {{-- Device Info --}}
                    <div class="flex-1 text-right">
                        <div class="text-sm font-bold text-gray-900">
                            {{ $session->agent }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <span dir="ltr">{{ $session->ip_address }}</span> -
                            @if ($session->is_current_device)
                                <span class="text-green-600 font-bold">هذا الجهاز</span>
                            @else
                                <span>آخر نشاط {{ $session->last_active }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Logout Button --}}
    <div class="flex flex-row items-center justify-start gap-4 mt-6 w-full">
        <button wire:click="confirmLogout" class="inline-flex justify-center rounded-md bg-gray-900 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-gray-700 transition">
            تسجيل الخروج من المتصفحات الأخرى
        </button>

        @if (session()->has('message'))
            <span class="text-sm font-bold text-green-600">
                {{ session('message') }}
            </span>
        @endif
    </div>

    {{-- Password Confirmation Modal --}}
    @if($confirmingLogout)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" dir="rtl">
            <div class="bg-white rounded-xl p-8 max-w-md w-full shadow-2xl animate-in zoom-in-95 duration-200 text-right">
                <h3 class="text-xl font-bold text-gray-900 mb-2">تأكيد تسجيل الخروج</h3>
                <p class="text-sm text-gray-500 mb-6 whitespace-normal">
                    يرجى إدخال كلمة المرور لتأكيد رغبتك في تسجيل الخروج من الأجهزة الأخرى.
                </p>

                <div class="mb-6">
                    <input wire:model="password" type="password" placeholder="كلمة المرور" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-md text-gray-900 focus:ring-2 focus:ring-blue-500 transition-all text-left" dir="ltr" autofocus>
                    @error('password') <span class="text-red-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-3 justify-end w-full">
                    <button wire:click="$set('confirmingLogout', false)" class="px-5 py-2.5 rounded-md text-sm font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                        إلغاء
                    </button>
                    <button wire:click="logoutOtherBrowserSessions" class="px-5 py-2.5 rounded-md text-sm font-bold text-white bg-gray-900 hover:bg-gray-800 shadow-md transition-colors">
                        تأكيد تسجيل الخروج
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
