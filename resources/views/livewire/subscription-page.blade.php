<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" dir="rtl">

    {{-- HEADER & ACTIONS --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-col gap-6">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"></path>
                    </svg>           
                    إدارة الإشتراكات
                </h1>
                <p class="text-sm text-slate-500 font-medium mt-1">عدد الإشتراكات: <span
                        class="text-amber-600 font-bold">{{ $subscriptions->total() }}</span></p>
            </div>

            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                {{-- @can('role.create')
                    <button wire:click="create" class="bg-amber-500 text-slate-900 px-6 py-2.5 rounded-xl font-bold hover:bg-amber-400 transition-all shadow-lg shadow-amber-100 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"></path>
                    </svg>
                        إضافة صلاحية جديدة
                    </button>
                @endcan --}}


                {{-- <button wire:click="$dispatch('open-sales-form')"
                    class="w-full sm:w-auto bg-slate-900 hover:bg-black text-amber-400 px-6 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    عملية جديدة
                </button> --}}
            </div>
        </div>
        {{-- SEARCH BAR --}}
        <div class="relative w-full border-t border-slate-100 pt-6">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pt-6 pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="ابحث بالاسم، رقم الهوية..."
                class="block w-full p-3 pr-10 text-sm text-slate-900 border border-slate-300 rounded-xl bg-slate-50 focus:ring-amber-500 focus:border-amber-500 transition-all">
            @if(!empty($search))
                <button wire:click="$set('search', '')" class="absolute inset-y-0 left-0 flex items-center pl-3 pt-6">
                    <svg class="w-4 h-4 text-slate-400 hover:text-red-500 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            @endif
        </div>
    </div>    
        {{-- subscriptions LIST --}}         

    <div class="w-full bg-white shadow-2xl rounded-3xl overflow-x-auto border border-gray-100">
        <table class="w-full text-right border-collapse whitespace-nowrap" dir="rtl">

            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="hidden md:table-cell p-4 text-sm font-bold uppercase tracking-wider">الإسم</th>
                    <th class="hidden md:table-cell p-4 text-sm font-bold uppercase tracking-wider">الشركة</th>
                    {{-- <th class="hidden md:table-cell p-4 text-sm font-bold uppercase tracking-wider">النوع</th> --}}
                    <th class="p-4 text-sm font-bold uppercase tracking-wider">البريد الإلكتروني</th>
                    {{-- <th class="hidden md:table-cell p-4 text-sm font-bold uppercase tracking-wider"> الجوال</th>
                    --}}
                    <th class="p-4 text-sm font-bold uppercase tracking-wider">الحالة</th>
                    <th class="hidden md:table-cell p-4 text-sm font-bold uppercase tracking-wider">تاريخ البدء</th>
                    <th class="p-4 text-sm font-bold uppercase tracking-wider">تاريخ الانتهاء</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($subscriptions as $sub)
                    <tr wire:click="openDetails({{ $sub->id }})"
                        class="hover:bg-indigo-50 transition-colors duration-200 cursor-pointer">

                        <td class="hidden md:table-cell p-4 font-bold text-gray-800">{{ $sub->name }}</td>
                        <td class="hidden md:table-cell p-4 font-bold text-gray-800">{{ $sub->company_name }}</td>
                        {{-- <td class="hidden md:table-cell p-4 text-sm text-gray-600">{{ $sub->type }}</td> --}}

                        <td class="p-4 text-sm text-indigo-600 font-mono">{{ $sub->email }}</td>

                        {{-- <td class="hidden md:table-cell p-4 text-sm text-indigo-600 font-mono">{{ $sub->phone_number }}
                        </td> --}}

                        <td class="p-4">
                            @php
                                $isExpired = \Carbon\Carbon::parse($sub->end_date)->isPast();
                                $isActive = !$isExpired && $sub->status == 'active';
                             @endphp
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold {{ $isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $isActive ? 'نشط' : 'منتهي' }}
                            </span>
                        </td>

                        <td class="hidden md:table-cell p-4 text-sm text-gray-500 font-mono">
                            {{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}
                        </td>

                        <td class="p-4 text-sm text-gray-500 font-mono">
                            {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-gray-400 font-bold">لا توجد اشتراكات مسجلة
                            حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 px-2">
        {{ $subscriptions->links() }}
    </div>

    {{-- the pop up --}}
    @if ($showModal && $selectedSub)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-all">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">

                <div class="bg-gray-800 p-5 flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold">تفاصيل الاشتراك</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">

                    <div class="flex justify-between items-center mb-4 border-b pb-4">
                        <span class="text-sm font-bold text-gray-500">حالة الاشتراك:</span>
                        @php
                            $isExpiredModal = \Carbon\Carbon::parse($selectedSub->end_date)->isPast();
                            $isActiveModal = !$isExpiredModal && $selectedSub->status == 'active';
                         @endphp
                        <span
                            class="px-4 py-1.5 rounded-full text-sm font-bold {{ $isActiveModal ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $isActiveModal ? 'نشط' : 'منتهي' }}
                        </span>

                    </div>

                    <div class="grid grid-cols-2 gap-y-4 gap-x-6 text-right">
                        <div>
                            <span class="block text-xs text-gray-400 font-bold mb-1">الإسم</span>
                            <span class="font-bold text-gray-800">{{ $selectedSub->name }}</span>
                        </div>

                        <div>
                            <span class="block text-xs text-gray-400 font-bold mb-1">الشركة</span>
                            <span class="font-bold text-gray-800">{{ $selectedSub->company_name }}</span>
                        </div>


                        <div>
                            <span class="block text-xs text-gray-400 font-bold mb-1">البريد الإلكتروني</span>
                            <span
                                class="text-indigo-600 font-mono bg-indigo-50 px-2 py-1 rounded">{{ $selectedSub->email }}</span>
                        </div>



                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <span class="block text-xs text-gray-400 font-bold mb-1">تاريخ البدء</span>
                            <span
                                class="text-sm text-gray-600 font-mono">{{ \Carbon\Carbon::parse($selectedSub->start_date)->format('d M Y') }}</span>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <span class="block text-xs text-gray-400 font-bold mb-1">تاريخ الانتهاء</span>
                            <span
                                class="text-sm text-gray-600 font-mono">{{ \Carbon\Carbon::parse($selectedSub->end_date)->format('d M Y') }}</span>
                        </div>

                    </div>

                </div>

                <div class="bg-gray-50 p-4 flex justify-end">
                    <button wire:click="closeModal"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-xl font-bold transition-all">إلغاء</button>
                </div>

            </div>
        </div>
    @endif
</div>
