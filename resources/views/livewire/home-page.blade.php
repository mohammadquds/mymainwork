<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" dir="rtl">

    {{-- HEADER & ACTIONS --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-col gap-6">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    إدارة المبيعات
                </h1>
                <p class="text-sm text-slate-500 font-medium mt-1">إجمالي العمليات المسجلة: <span class="text-amber-600 font-bold">{{ $sales->total() }}</span></p>
            </div>

            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                <button @click="$dispatch('open-calculator-modal')"
                        class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    الحاسبة
                </button>


                <button wire:click="$dispatch('open-sales-form')"
                        class="w-full sm:w-auto bg-slate-900 hover:bg-black text-amber-400 px-6 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    عملية جديدة
                </button>
            </div>
        </div>

        {{-- SEARCH BAR --}}
        <div class="relative w-full border-t border-slate-100 pt-6">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pt-6 pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="ابحث بالاسم، رقم الهوية..." class="block w-full p-3 pr-10 text-sm text-slate-900 border border-slate-300 rounded-xl bg-slate-50 focus:ring-amber-500 focus:border-amber-500 transition-all">
            @if(!empty($search))
                <button wire:click="$set('search', '')" class="absolute inset-y-0 left-0 flex items-center pl-3 pt-6">
                    <svg class="w-4 h-4 text-slate-400 hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            @endif
        </div>
    </div>


    {{-- SALES LIST --}}
    <div class="space-y-3">
        @forelse($sales as $sale)
            <div wire:click="openDetails({{ $sale->id }})" wire:key="sale-{{ $sale->id }}"
                 class="group bg-white border border-slate-200 p-4 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-4 cursor-pointer hover:border-amber-400 hover:shadow-md transition-all duration-200">

                <div class="flex items-center gap-4 w-full md:w-1/3">
                    <div class="bg-slate-50 border border-slate-100 text-slate-500 w-12 h-12 rounded-xl flex items-center justify-center font-black shadow-sm group-hover:bg-amber-100 group-hover:text-amber-700 group-hover:border-amber-200 transition-colors">
                      #{{ $loop->iteration }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 group-hover:text-amber-600 transition-colors">{{ $sale->full_name }}</h3>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $sale->national_id }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-around w-full md:w-1/3 md:border-r md:border-l border-slate-100 px-4 py-2 md:py-0 bg-slate-50 md:bg-transparent rounded-xl md:rounded-none">
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">العيار</p>
                        <p class="text-sm font-bold text-slate-800">{{ $sale->karat }}<span class="text-xs text-amber-500 ml-0.5">K</span></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">الوزن</p>
                        <p class="text-sm font-bold text-slate-800">{{ $sale->weight }}<span class="text-xs text-amber-500 ml-0.5">g</span></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">التاريخ</p>
                        <p class="text-sm font-bold text-slate-800">{{ $sale->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-1/3">
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">المبلغ الإجمالي</p>
                        <p class="text-xl font-black text-slate-900">{{ number_format($sale->weight * $sale->sale_price, 2) }} <span class="text-xs text-slate-400">SAR</span></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button wire:click.stop="delete({{ $sale->id }})" wire:confirm="تأكيد حذف هذه العملية؟"
                                class="text-slate-300 hover:text-red-500 bg-slate-50 hover:bg-red-50 p-2 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                        <div class="text-slate-300 group-hover:text-amber-500 transition-colors hidden sm:block">
                            <svg class="w-6 h-6 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white py-16 text-center rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <p class="text-slate-500 font-bold text-lg">لا توجد مبيعات مسجلة</p>
                <p class="text-slate-400 text-sm mt-1">قم بإضافة عملية شراء جديدة للبدء</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8" dir="ltr">
        {{ $sales->links() }}
    </div>


    {{-- CALCULATOR MODAL --}}
    <div x-data="{ showModal: false }" @open-calculator-modal.window="showModal = true" @close-calculator-modal.window="showModal = false">
        <div x-show="showModal" x-cloak style="display: none;" class="fixed inset-0 z-[60] flex flex-col items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" dir="rtl">
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden" @click.away="showModal = false" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="bg-slate-900 p-4 flex justify-between items-center text-white">
                    <span class="font-bold text-amber-400">الحاسبة السريعة</span>
                    <button @click="showModal = false" class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6">
                    <livewire:quick-calculator />
                </div>
            </div>
        </div>
    </div>


    {{-- EXCEL MODAL --}}
    @if($showExcelModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all" dir="rtl">
        <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden flex flex-col">
            <div class="bg-slate-900 p-5 flex justify-between items-center text-white">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-slate-800 rounded-lg text-green-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-amber-400">تصدير إلى إكسل</h3>
                </div>
                <button wire:click="closeExcelModal" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                <p class="text-sm text-slate-500">حدد الفترة الزمنية التي ترغب في تصدير بياناتها.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">من تاريخ</label>
                        <input type="date" wire:model="startDate" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm transition-all bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">إلى تاريخ</label>
                        <input type="date" wire:model="endDate" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm transition-all bg-slate-50">
                    </div>
                </div>
                @if($startDate || $endDate)
                <button wire:click="$set('startDate', null); $set('endDate', null)" class="text-xs text-red-500 hover:text-red-700 font-bold flex items-center gap-1 transition-colors mt-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    مسح التواريخ
                </button>
                @endif
            </div>

            <div class="bg-slate-50 p-6 flex flex-col md:flex-row-reverse justify-start gap-3 border-t border-slate-100">
                <button wire:click="exportExcel" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-md transition-all">تأكيد التصدير</button>
                <button wire:click="closeExcelModal" class="w-full md:w-auto bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-6 py-2.5 rounded-xl font-bold transition-all shadow-sm">إلغاء</button>
            </div>
        </div>
    </div>
    @endif


 {{-- INVOICE DETAILS MODAL (CSS Grid Layout Fix for iOS) --}}
    @if($showModal && $selectedSale)

    {{-- 1. SINGLE WRAPPER: wire:key prevents Livewire scrambling --}}
    <div wire:key="invoice-modal-{{ $selectedSale->id }}" class="fixed inset-0 z-[100] bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6" dir="rtl">

        {{-- 2. MODAL CONTAINER: Changed to CSS Grid. '85dvh' adapts to the iPhone URL bar perfectly. --}}
        <div class="bg-slate-50 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden relative grid"
             style="height: 85dvh; max-height: 800px; grid-template-rows: auto minmax(0, 1fr) auto;">

            {{-- 3. HEADER (Row 1) --}}
            <div class="bg-slate-900 p-4 flex justify-between items-center text-white border-b border-amber-500/20 z-20">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-slate-800 rounded-lg text-amber-400 border border-slate-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-sm font-black text-white">فاتورة <span class="text-amber-400 font-mono">#{{ $selectedSale->id }}</span></h3>
                </div>
                <button wire:click="closeModal" class="text-slate-400 hover:text-white bg-slate-800 p-1.5 rounded-full border border-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- 4. BODY (Row 2: minmax(0, 1fr) forces iOS to scroll this section instead of stretching it) --}}
            <div class="overflow-y-auto w-full p-4 bg-slate-50/50 min-h-0" style="-webkit-overflow-scrolling: touch;">

                {{-- Client Info --}}
                <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm mb-3 text-center">
                    <p class="text-sm font-black text-slate-900 mb-1">{{ $selectedSale->full_name }}</p>
                    <div class="flex flex-col gap-1 text-[10px] text-slate-500">
                        <span><strong class="text-slate-400">الهوية:</strong> <span class="font-mono">{{ $selectedSale->national_id }}</span></span>
                        <span><strong class="text-slate-400">النسخة:</strong> <span class="font-mono">{{ $selectedSale->id_version_number }}</span></span>
                    </div>
                </div>

                {{-- Main Receipt Data --}}
                <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm mb-3">
                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-bold text-xs text-slate-500">العيار</span>
                        <div class="font-black text-slate-900 text-sm">{{ $selectedSale->karat }} <span class="text-[9px] text-amber-500">K</span></div>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-bold text-xs text-slate-500">الوزن الصافي</span>
                        <div class="font-black text-slate-900 text-sm">{{ $selectedSale->weight }} <span class="text-[9px] text-amber-500">g</span></div>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100">
                        <span class="font-bold text-xs text-slate-500">سعر الجرام</span>
                        <div class="font-black text-slate-900 text-sm">{{ number_format($selectedSale->sale_price, 2) }} <span class="text-[9px] text-slate-400">SAR</span></div>
                    </div>

        {{-- Image Viewer --}}
                    <div class="flex items-center justify-between py-2" x-data="{ showImageModal: false }">
                        <span class="font-bold text-xs text-slate-500">المرفقات</span>
                        @if($selectedSale->product_image)
                            <button @click="showImageModal = true" class="text-[10px] bg-amber-50 text-amber-600 px-2 py-1 rounded font-bold border border-amber-100">عرض الصورة</button>

                            {{-- THE FIX: Removed x-teleport to stop the massive Livewire memory leak --}}
                            <div x-show="showImageModal" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-slate-900/90 p-4">
                                <div class="relative bg-white p-2 rounded-xl shadow-2xl" @click.away="showImageModal = false" style="width: 100%; max-width: 300px;">
                                    <button @click="showImageModal = false" class="absolute -top-3 -right-3 bg-red-500 text-white p-1.5 rounded-full shadow-lg border-2 border-white">
                                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <img src="{{ asset('storage/' . $selectedSale->product_image) }}" class="w-full rounded-lg bg-slate-100" style="max-height: 300px; object-fit: contain;">
                                </div>
                            </div>
                        @else
                            <span class="text-[10px] text-slate-400">لا توجد صورة</span>
                        @endif
                    </div>
                </div>

                {{-- Total & Store --}}
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm text-center mb-3">
                    <span class="text-[9px] font-black text-slate-400 uppercase">إجمالي المبلغ المدفوع</span>
                    <div class="font-black text-3xl text-slate-900 mb-3">{{ number_format($selectedSale->weight * $selectedSale->sale_price, 2) }} <span class="text-xs text-amber-500">ريال</span></div>
                    <div class="border-t border-slate-100 pt-3 grid grid-cols-2 gap-2">
                        <div><span class="block text-[8px] text-slate-400">اسم المحل</span><span class="block text-[10px] font-black text-slate-700 truncate">{{ $selectedSale->store_name }}</span></div>
                        <div class="border-r border-slate-100"><span class="block text-[8px] text-slate-400">الموظف</span><span class="block text-[10px] font-black text-slate-700 truncate">{{ $selectedSale->employee_name }}</span></div>
                    </div>
                </div>

                {{-- Previous Orders --}}
                <div class="w-full border-t border-slate-200 pt-3 pb-2">
                    <h4 class="text-xs font-black text-slate-900 mb-2 flex items-center gap-1.5">
                        <div class="p-1 bg-amber-100 text-amber-600 rounded"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>
                        سجل طلبات العميل <span class="text-amber-500">({{ $customerOrders->total() }})</span>
                    </h4>
                    <div class="space-y-2">
                        @foreach($customerOrders as $order)
                            <button type="button" wire:click="openDetails({{ $order->id }})" class="w-full bg-white p-2.5 rounded-lg border border-slate-200 shadow-sm flex justify-between items-center hover:border-amber-400 transition-colors focus:outline-none">
                                <div class="text-right">
                                    <span class="text-[11px] font-black text-slate-800 block">طلب <span class="text-amber-600">#{{ $order->id }}</span></span>
                                </div>
                                <div class="flex items-center gap-3 text-left">
                                    <div><p class="text-[8px] text-slate-400">الوزن</p><p class="text-[11px] font-black text-slate-700">{{ $order->weight }} ج</p></div>
                                    <div class="border-r border-slate-100 pr-2"><p class="text-[8px] text-slate-400">الإجمالي</p><p class="text-[11px] font-black text-slate-900">{{ number_format($order->weight * $order->sale_price, 2) }}</p></div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-3" dir="ltr">{{ $customerOrders->links() }}</div>
                </div>

            </div>

            {{-- 5. FOOTER (Row 3) --}}
            <div class="bg-white p-3 sm:p-4 flex gap-2 sm:gap-3 items-center border-t border-slate-200 z-20">
                <button wire:click="closeModal" class="flex-1 bg-slate-100 text-slate-700 py-2.5 rounded-lg text-sm font-bold border border-slate-200 shadow-sm hover:bg-slate-200 transition-colors">إغلاق</button>
                <button onclick="window.print()" class="flex-1 bg-slate-900 text-amber-400 py-2.5 rounded-lg text-sm font-bold flex justify-center items-center gap-1.5 shadow-md hover:bg-black transition-colors">
                    <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    طباعة
                </button>
            </div>

        </div>
    </div>
    @endif

    <livewire:sales-form />
</div>
