<nav class="bg-gray-900 shadow-md" x-data="{ mobileMenuOpen: false }" dir="rtl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center">
                <div class="flex sm:hidden mr-2">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-gray-300 hover:bg-gray-800 hover:text-white p-2 rounded-md transition-colors">
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="hidden sm:flex space-x-4">
                    <a href="{{ route('home.page') }}" wire:navigate
                        class="{{ request()->routeIs('home.page') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-md px-3 py-2 text-sm font-bold transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5 inline-flex mx-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        الصفحة الرئيسية</a>

                    @can('user.view')
                        <a href="{{ route('user.page') }}" wire:navigate
                            class="{{ request()->routeIs('user.page') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-md px-3 py-2 text-sm font-bold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5 inline-flex mx-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"></path>
                            </svg>المستخدمون</a>
                    @endcan

                    @can('role.view')
                        <a href="{{ route('role.page') }}" wire:navigate
                            class="{{ request()->routeIs('role.page') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-md px-3 py-2 text-sm font-bold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5 inline-flex mx-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"></path>
                            </svg>الصلاحيات</a>
                    @endcan

                    @can('subscription.unactive.view')
                        <a href="{{ route('subscription.page') }}" wire:navigate
                            class="{{ request()->routeIs('subscription.page') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-md px-3 py-2 text-sm font-bold transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5 inline-flex mx-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                            </svg>
                            الإشتراكات</a>
                    @endcan


                    <a href="{{ route('activity.log') }}" wire:navigate
                        class="{{ request()->routeIs('activity.log') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} rounded-md px-3 py-2 text-sm font-bold transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5 inline-flex mx-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z">
                            </path>
                        </svg>سجلات الانشطة</a>


                    <div class="relative inline-block text-right" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" type="button"
                            class="flex items-center gap-2 text-gray-300 hover:bg-gray-800 hover:text-white px-3 py-2 rounded-md transition-all duration-200 group">
                            <span class="font-bold text-sm">رؤية التقارير</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            class="absolute left-0 mt-2 w-56 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                            style="display: none;">

                            <div class="p-1">
                                <div class="my-1 border-t border-gray-100"></div>
                                <button @click="$dispatch('triggerExcelModal'); open = false"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-100 transition-colors rounded-lg text-right">
                                    <span class="text-lg"></span>
                                    <span>نقل إلى إكسل(Excel)</span>
                                </button>

                                <div class="my-1 border-t border-gray-100"></div>

                                <button @click="$dispatch('triggerPdfModal'); open = false"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-red-100 transition-colors rounded-lg text-right">
                                    <span class="text-lg"></span>
                                    <span>نقل إلى (PDF)</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <div x-data="{ profileMenuOpen: false }" class="relative ml-3">
                    <button @click="profileMenuOpen = !profileMenuOpen" @click.away="profileMenuOpen = false"
                        class="flex items-center gap-2 rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white p-1">
                        <span
                            class="text-white font-bold hidden md:block px-2">{{ auth()->user()->name ?? 'Guest' }}</span>
                        <div
                            class="h-8 w-8 rounded-full bg-black flex items-center justify-center text-white font-black">
                            {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="profileMenuOpen" x-transition style="display: none;"
                        class="absolute left-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                        <a href="{{ route('profile.page') }}" wire:navigate
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-bold">اعدادات الحساب</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-right px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold">تسجيل
                                الخروج</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- here is the mobile view --}}
    <div x-show="mobileMenuOpen" x-collapse style="display: none;" class="sm:hidden border-t border-gray-800"
        id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2">
            <a href="{{ route('home.page') }}" wire:navigate
                class="{{ request()->routeIs('home.page') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} block rounded-md px-3 py-2 text-base font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5 inline-flex mx-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                الصفحة الرئيسية</a>

            @can('user.view')
                <a href="{{ route('user.page') }}" wire:navigate
                    class="{{ request()->routeIs('user.page') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} block rounded-md px-3 py-2 text-base font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 inline-flex mx-1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7Z"></path>
                    </svg>المستخدمون</a>
            @endcan

            @can('role.view')
                <a href="{{ route('role.page') }}" wire:navigate
                    class="{{ request()->routeIs('role.page') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} block rounded-md px-3 py-2 text-base font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 inline-flex mx-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z">
                        </path>
                    </svg>الصلاحيات</a>
            @endcan

            @can('subscription.unactive.view')
                <a href="{{ route('subscription.page') }}" wire:navigate
                    class="{{ request()->routeIs('subscription.page') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} block rounded-md px-3 py-2 text-base font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 inline-flex mx-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>
                    الإشتراكات</a>
            @endcan


            <a href="{{ route('activity.log') }}" wire:navigate
                class="{{ request()->routeIs('activity.log') ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} block rounded-md px-3 py-2 text-base font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5 inline-flex mx-1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z">
                    </path>
                </svg>سجلات الانشطة</a>


            <div x-data="{ openMobileReports: false }" class="mt-1">
                <button @click="openMobileReports = !openMobileReports" type="button"
                    class="w-full flex items-center justify-between text-gray-300 hover:bg-gray-800 hover:text-white px-3 py-2 rounded-md text-base font-bold transition-colors">
                    <span>رؤية التقارير</span>
                    <svg class="w-5 h-5 transition-transform duration-200"
                        :class="openMobileReports ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="openMobileReports" x-collapse class="bg-gray-800 rounded-md mt-1 overflow-hidden"
                    style="display: none;">
                    <div class="px-2 py-2 space-y-1">
                        <button @click="$dispatch('triggerExcelModal'); open = false"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-gray-800 hover:bg-green-100 transition-colors rounded-lg text-right">
                            <span class="text-lg"></span>
                            <span>نقل إلى إكسل(Excel)</span>
                        </button>

                        <div class="my-1 border-t border-gray-600"></div>

                        <button @click="$dispatch('triggerPdfModal'); open = false"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:text-gray-800 hover:bg-red-100 transition-colors rounded-lg text-right">
                            <span class="text-lg"></span>
                            <span>نقل إلى (PDF)</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>
