<div class="min-h-screen w-full flex bg-white" dir="rtl">

    <div class="hidden lg:flex lg:w-1/2 bg-slate-900 relative overflow-hidden flex-col justify-center items-center p-12">
        {{-- Background Decorative Elements --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-amber-500/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-blue-500/10 blur-3xl"></div>

        <div class="relative z-10 text-center">
            {{-- Logo Placeholder --}}
            <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl mx-auto mb-8 shadow-lg shadow-amber-500/30 flex items-center justify-center">
                <svg class="w-10 h-10 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-black text-white mb-4 tracking-tight">نظام إدارة الذهب</h1>
            <p class="text-slate-400 text-lg max-w-md mx-auto leading-relaxed">
                منصة متكاملة وموثوقة لإدارة مبيعاتك، مخزونك، وعملائك بكل سهولة وأمان.
            </p>
        </div>
    </div>

    {{-- LEFT SIDE THE FORM PANEL --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12 sm:px-12 bg-white relative">

        {{-- Top Toggle Button --}}
        <div class="absolute top-8 left-8">
            <button type="button" wire:click="toggleMode" class="text-sm font-bold text-slate-500 hover:text-amber-600 transition-colors flex items-center gap-2">
                {{ $isLoginMode ? "إنشاء حساب جديد" : "تسجيل الدخول" }}
                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <div class="w-full max-w-md mt-10 lg:mt-0">

            {{-- Headers --}}
            <div class="mb-10">
                <h2 class="text-3xl font-black text-slate-900">
                    {{ $isLoginMode ? 'مرحباً بعودتك' : 'حساب جديد' }}
                </h2>
                <p class="mt-2 text-slate-500 font-medium">
                    {{ $isLoginMode ? 'قم بتسجيل الدخول للوصول إلى لوحة التحكم.' : 'املأ البيانات التالية للبدء في استخدام النظام.' }}
                </p>
            </div>

            @if($isLoginMode)
                {{-- LOGIN FORM --}}
                <form wire:submit.prevent="loginUser" wire:key="login-form" class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">البريد الإلكتروني</label>
                        <input wire:model="log_email" type="email" placeholder="name@company.com" class="block w-full px-4 py-3 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 transition-all sm:text-sm">
                        @error('log_email') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">كلمة المرور</label>
                        <input wire:model="log_password" type="password" placeholder="••••••••" class="block w-full px-4 py-3 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 transition-all sm:text-sm text-left" dir="ltr">
                        @error('log_password') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <div class="flex items-center gap-2">
                            <input wire:model="remember" type="checkbox" class="w-4 h-4 text-amber-600 bg-slate-50 border-slate-300 rounded focus:ring-amber-500">
                            <label class="text-sm font-medium text-slate-600">تذكرني</label>
                        </div>
                        <a href="#" class="text-sm font-bold text-amber-600 hover:text-amber-500">نسيت كلمة المرور؟</a>
                    </div>

                    <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-sm font-black text-slate-900 bg-amber-500 hover:bg-amber-400 shadow-lg shadow-amber-500/30 transition-all mt-4">
                        تسجيل الدخول
                    </button>
                </form>

            @else
                {{-- REGISTRATION MULTI-STEP FORM --}}

                {{-- STEP INDICATOR --}}
                <div class="flex items-center gap-2 mb-8">
                    <div class="h-2 rounded-full flex-1 transition-colors duration-300 {{ $currentStep >= 1 ? 'bg-amber-500' : 'bg-slate-100' }}"></div>
                    <div class="h-2 rounded-full flex-1 transition-colors duration-300 {{ $currentStep >= 2 ? 'bg-amber-500' : 'bg-slate-100' }}"></div>
                    <div class="h-2 rounded-full flex-1 transition-colors duration-300 {{ $currentStep >= 3 ? 'bg-amber-500' : 'bg-slate-100' }}"></div>
                </div>

                {{-- STEP 1: EMAIL INPUT --}}
                @if($currentStep === 1)
                    <form wire:submit.prevent="sendOtp" wire:key="step-1" class="space-y-5 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">البريد الإلكتروني </label>
                            <input wire:model="sign_email" type="email" placeholder="name@company.com" class="block w-full px-4 py-3 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 transition-all sm:text-sm text-left" dir="ltr">
                            @error('sign_email') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-sm font-black text-white bg-slate-900 hover:bg-slate-800 shadow-lg shadow-slate-900/20 transition-all">
                            متابعة
                        </button>
                    </form>

                {{-- STEP 2: OTP VERIFICATION --}}
                @elseif($currentStep === 2)
                    <form wire:submit.prevent="verifyOtp" wire:key="step-2" class="space-y-5 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 mb-6">
                            <p class="text-sm font-medium text-amber-800 leading-relaxed text-center">
                                لقد أرسلنا رمز التحقق إلى<br>
                                <span class="font-bold block mt-1" dir="ltr">{{ $sign_email }}</span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5 text-center">رمز التحقق</label>
                            <input wire:model="enteredOtp" type="text" inputmode="numeric" maxlength="6" placeholder="------"
                                class="block w-full px-4 py-4 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 transition-all text-3xl font-mono tracking-[0.5em] text-center">
                            @error('enteredOtp') <span class="text-red-500 text-xs font-bold mt-2 block text-center">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-center">
                            <button type="button" wire:click="sendOtp" class="text-xs font-bold text-slate-500 hover:text-amber-600 transition-colors">لم تستلم الرمز؟ إعادة إرسال</button>
                        </div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" wire:click="stepBack" class="w-1/3 py-3.5 px-4 rounded-xl text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all">
                                رجوع
                            </button>
                            <button type="submit" class="w-2/3 py-3.5 px-4 rounded-xl text-sm font-black text-white bg-slate-900 hover:bg-slate-800 shadow-lg shadow-slate-900/20 transition-all">
                                تحقق
                            </button>
                        </div>
                    </form>

              {{-- STEP 3: FINAL DETAILS --}}
                @elseif($currentStep === 3)
                    <form wire:submit.prevent="registerUser" wire:key="step-3" class="space-y-4 animate-in fade-in slide-in-from-bottom-4 duration-500">

                        {{-- Row 1: Name and Company --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">الاسم  <span class="text-red-500">*</span></label>
                                <input wire:model="name" type="text" class="block w-full px-3 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm">
                                @error('name') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم الشركة <span class="text-red-500">*</span></label>
                                <input wire:model="company_name" type="text"
                                    @if($isCompanyLocked) readonly class="block w-full px-3 py-2.5 bg-slate-100 border-0 ring-1 ring-inset ring-slate-200 rounded-lg text-slate-500 cursor-not-allowed sm:text-sm"
                                    @else class="block w-full px-3 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm"
                                    @endif>
                                @error('company_name') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Row 2: Mobile Number (Full Width) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الجوال <span class="text-red-500">*</span></label>
                            <input wire:model="mobile_number" type="text" class="block w-full px-3 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm text-left" dir="ltr">
                            @error('mobile_number') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Row 3: Passwords --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-4 mt-2">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">كلمة المرور <span class="text-red-500">*</span></label>
                                <input wire:model="sign_password" type="password" class="block w-full px-3 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm text-left" dir="ltr">
                                @error('sign_password') <span class="text-red-500 text-[10px] font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
                                <input wire:model="sign_password_confirmation" type="password" class="block w-full px-3 py-2.5 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-lg text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm text-left" dir="ltr">
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 px-4 rounded-xl text-sm font-black text-slate-900 bg-amber-500 hover:bg-amber-400 shadow-lg shadow-amber-500/30 transition-all">
                                إتمام التسجيل
                            </button>
                        </div>
                    </form>
                @endif
            @endif
        </div>
    </div>
</div>
