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
                    <th class="p-4 text-sm font-bold uppercase tracking-wider">الإسم</th>
                    <th class="hidden md:table-cell p-4 text-sm font-bold uppercase tracking-wider">الشركة</th>
                    <th class="p-4 text-sm font-bold uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="hidden md:table-cell p-4 text-sm font-bold uppercase tracking-wider"> رقم الجوال</th>
                    <th class="hidden md:table-cell p-4 text-sm font-bold uppercase tracking-wider">  الرقم الموحد \ رقم السجل التجاري </th>
                    <th class="hidden md:table-cell p-4 text-sm font-bold uppercase tracking-wider"> الرقم الضريبي (VAT)</th>
                </tr>
            </thead>

            {{-- LOOP STARTS HERE --}}
            @forelse($subscriptions as $sub)
             @php
                    $children = $sub->children;
                @endphp

                {{-- 1. NEW TBODY WRAPPER: Each Admin gets their own tbody to control the Alpine state --}}
                <tbody x-data="{ expanded: false }" class="border-b border-gray-100 last:border-0">

                    {{-- THE MAIN ADMIN / BOSS ROW --}}
                    <tr class="hover:bg-indigo-50 transition-colors duration-200 cursor-pointer">

                        {{-- Name Column with the Expand Button --}}
                        <td class="p-4 font-bold text-gray-800">
                            <div class="flex items-center gap-3">

                                {{-- The Expand Arrow Button --}}
                                @if($children && $children->count() > 0)                                    {{-- @click.stop prevents the modal from opening when you click the arrow --}}
                                    <button @click.stop="expanded = !expanded"
                                            class="p-1 rounded-full bg-slate-100 text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 transition-all border border-slate-200 shadow-sm">
                                        <svg :class="{'rotate-180': expanded}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                @else
                                    {{-- Empty space to keep names perfectly aligned if they have no employees --}}
                                    <div class="w-6 h-6"></div>
                                @endif

                                {{-- Clicking the name opens the modal --}}
                                <span wire:click="openDetails({{ $sub->id }})" class="hover:text-indigo-600 transition-colors">{{ $sub->name }}</span>
                            </div>
                        </td>

                        {{-- Clicking these columns opens the modal --}}
                        <td wire:click="openDetails({{ $sub->id }})" class="hidden md:table-cell p-4 font-bold text-gray-800">{{ $sub->company_name }}</td>
                        <td wire:click="openDetails({{ $sub->id }})" class="p-4 text-sm text-indigo-600 font-mono">{{ $sub->email }}</td>
                        <td wire:click="openDetails({{ $sub->id }})" class="hidden md:table-cell p-4 text-sm text-indigo-600 font-mono">{{ $sub->mobile_number }}</td>
                        <td wire:click="openDetails({{ $sub->id }})" class="hidden md:table-cell p-4 font-bold text-gray-800">{{ $sub->official_company_number }}</td>
                        <td wire:click="openDetails({{ $sub->id }})" class="hidden md:table-cell p-4 font-bold text-gray-800">{{ $sub->vat_number }}</td>
                    </tr>

                    {{-- THE CHILDREN / EMPLOYEES ROWS --}}
                    @foreach($children as $child)
                        {{-- 2. x-show: These rows are hidden until the arrow is clicked! --}}
                        {{-- 1. Added wire:click and cursor-pointer to the row --}}
                        <tr wire:click="openDetails({{ $child->id }})" x-show="expanded" x-cloak style="display: none;" class="bg-slate-50/80 hover:bg-slate-100 transition-colors duration-200 border-t border-slate-100 cursor-pointer">
                            <td class="p-4 font-semibold text-slate-600 pr-12 border-r-2 border-indigo-300">
                                <div class="flex items-center">
                                    <span class="text-indigo-400 font-black ml-2 text-lg">↳</span>
                                    {{ $child->name }}
                                    <span class="text-[10px] bg-white border border-slate-200 text-slate-600 px-2 py-0.5 rounded-full mr-3 font-bold shadow-sm">موظف</span>
                                </div>
                            </td>
                            <td class="hidden md:table-cell p-4 text-slate-500 text-sm">{{ $child->company_name ?? $sub->company_name }}</td>
                            <td class="p-4 text-sm text-slate-500 font-mono">{{ $child->email }}</td>
                            <td class="hidden md:table-cell p-4 text-slate-500 text-sm">{{ $child->mobile_number }}</td>
                            <td class="hidden md:table-cell p-4 text-slate-500 text-sm">{{ $child->official_company_number }}</td>
                            <td class="hidden md:table-cell p-4 text-slate-500 text-sm">{{ $child->vat_number }}</td>
                        </tr>
                    @endforeach

                </tbody>
            @empty
                <tbody>
                    <tr>
                        <td colspan="3" class="p-10 text-center text-gray-400 font-bold">لا توجد اشتراكات مسجلة حالياً.</td>
                    </tr>
                </tbody>
            @endforelse
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
                            <span class="text-indigo-600 font-mono bg-indigo-50 px-2 py-1 rounded">{{ $selectedSub->email }}</span>
                        </div>

                           <div>
                            <span class="block text-xs text-gray-400 font-bold mb-1">  رقم الجوال</span>
                            <span class="text-indigo-600 font-mono bg-indigo-50 px-2 py-1 rounded">{{ $selectedSub->mobile_number }}</span>
                        </div>


                         <div>
                            <span class="block text-xs text-gray-400 font-bold mb-1">  الرقم الموحد \ رقم السجل التجاري </span>
                            <span class="font-bold text-gray-800">{{ $selectedSub->official_company_number }}</span>
                        </div>

                         <div>
                            <span class="block text-xs text-gray-400 font-bold mb-1"> الرقم الضريبي (VAT)</span>
                            <span class="font-bold text-gray-800">{{ $selectedSub->vat_number }}</span>
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
