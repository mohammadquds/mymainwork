<div class="w-full px-4 py-6 text-right" dir="rtl">
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-8 border border-gray-200">

            <h2 class="text-2xl font-bold text-gray-900 mb-2">معلومات الحساب</h2>
            <p class="text-sm text-gray-500 mb-6">قم بتحديث معلومات حسابك وعنوان بريدك الإلكتروني.</p>

            @if (session()->has('message'))
                <div class="mb-6 p-4 text-green-700 bg-green-100 rounded-md border border-green-200">
                    {{ session('message') }}
                </div>
            @endif


            <form wire:submit.prevent="updateProfile" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">الاسم</label>
                    <input type="text" wire:model="name"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                    <input type="email" wire:model="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <button type="submit"
                        class="inline-flex justify-center rounded-md bg-gray-900 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-gray-700 transition">
                        حفظ
                    </button>
                </div>
            </form>
        </div>


        <div class="hidden sm:block">
            <div class="py-8">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        {{-- here to reset your password --}}
        <div class="bg-white shadow sm:rounded-lg p-8 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">تحديث كلمة المرور</h2>
            <p class="text-sm text-gray-500 mb-6">تأكد من أن حسابك يستخدم كلمة مرور طويلة وعشوائية للبقاء آمنًا.</p>


            <form wire:submit.prevent="updatePassword" class="space-y-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700">كلمة المرور الحالية</label>
                    <input type="password" wire:model="current_password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border text-gray-900">
                    @error('current_password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">كلمة المرور الجديدة</label>
                    <input type="password" wire:model="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border text-gray-900">
                    @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">تأكيد كلمة المرور</label>
                    <input type="password" wire:model="password_confirmation"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border text-gray-900">
                    @error('password_confirmation') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <button type="submit"
                        class="inline-flex justify-center rounded-md bg-gray-900 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-gray-700 transition">
                        حفظ كلمة المرور
                    </button>

                    <span x-data="{ shown: false, timeout: null }"
                        x-on:password-updated.window="clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);"
                        x-show="shown" x-transition style="display: none;" class="text-sm text-green-600 font-medium">
                        Saved.
                    </span>
                </div>
            </form>
        </div>

        <div class="hidden sm:block">
            <div class="py-8">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        {{--  BROWSER SESSIONS  --}}
        <livewire:browser-sessions />

    </div>
</div>
