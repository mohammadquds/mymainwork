<nav class="bg-gray-800 shadow-md" x-data="{ mobileMenuOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center">
                <div class="flex sm:hidden mr-2">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-400 hover:bg-gray-700 hover:text-white p-2 rounded-md">
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                        <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="hidden sm:flex space-x-4">
                    <a href="{{ route('home.page') }}" wire:navigate class="{{ request()->routeIs('home.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-md px-3 py-2 text-sm font-medium transition">Home</a>

                    @can('user.view')
                        <a href="{{ route('user.page') }}" wire:navigate class="{{ request()->routeIs('user.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-md px-3 py-2 text-sm font-medium transition">Users</a>
                    @endcan

                    @can('role.view')
                        <a href="{{ route('role.page') }}" wire:navigate class="{{ request()->routeIs('role.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-md px-3 py-2 text-sm font-medium transition">Roles</a>
                    @endcan
                    @can('subscription.unactive.view')
                    <a href="{{ route('subscription.page') }}" wire:navigate class="{{ request()->routeIs('subscription.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-md px-3 py-2 text-sm font-medium transition">Subscriptions</a>
                    @endcan
                    <a href="{{ route('activity-logs.page') }}" wire:navigate class="{{ request()->routeIs('activity-logs.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-md px-3 py-2 text-sm font-medium transition">Activity Logs</a>
                    {{-- <a href="{{ route('activity-logs.page') }}" wire:navigate class="{{ request()->routeIs('activity-logs.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700' }} rounded-md px-3 py-2 text-sm font-medium transition">Activity Logs</a> --}}

                    <div class="relative inline-block text-right" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" type="button" 
                            class="flex items-center gap-2 text-gray-400 hover:bg-gray-700 hover:text-white p-2 rounded-md shadow-md transition-all duration-200 group">
                            <span class="font-bold text-sm">اصدار التقارير</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            class="absolute left-0 mt-2 w-56 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden">
                            
                            <div class="p-1">
                                <a href="{{ route('reports.viewpdf') }}" target="_blank"
                                    class="group flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors rounded-lg">
                                    <span class="text-lg">👁️</span>
                                    <span>(PDF)عرض تقرير</span>
                                </a>

                                <a href="{{ route('reports.generatepdf') }}" 
                                    class="group flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors rounded-lg">
                                    <span class="text-lg">📥</span>
                                    <span>(PDF)تحميل تقرير</span>
                                </a>

                                <div class="my-1 border-t border-gray-100"></div>

                                <button @click="$dispatch('triggerExcelModal'); open = false" 
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors rounded-lg text-right">
                                    <span class="text-lg">📥</span>
                                    <span>(Excel)نقل إلى إكسل</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <div x-data="{ profileMenuOpen: false }" class="relative ml-3">
                    <button @click="profileMenuOpen = !profileMenuOpen" @click.away="profileMenuOpen = false" class="flex items-center gap-2 rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white">
                        <span class="text-white font-medium hidden md:block">{{ auth()->user()->name ?? 'Guest' }}</span>
                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="profileMenuOpen" x-transition style="display: none;" class="absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                        <a href="{{ route('profile.page') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Log out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" style="display: none;" class="sm:hidden border-t border-gray-700" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2">
            <a href="{{ route('home.page') }}" wire:navigate class="{{ request()->routeIs('home.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">Home</a>

            @can('user.view')
                <a href="{{ route('user.page') }}" wire:navigate class="{{ request()->routeIs('user.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">Users</a>
            @endcan

            @can('role.view')
                <a href="{{ route('role.page') }}" wire:navigate class="{{ request()->routeIs('role.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">Roles</a>
            @endcan

            <a href="{{ route('subscription.page') }}" wire:navigate class="{{ request()->routeIs('subscription.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">Subscriptions</a>
            <a href="{{ route('activity-logs.page') }}" wire:navigate class="{{ request()->routeIs('activity-logs.page') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} block rounded-md px-3 py-2 text-base font-medium">Activity Logs</a>
        </div>
    </div>
</nav>
