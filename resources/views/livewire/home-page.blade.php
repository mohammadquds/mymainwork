<div class="max-w-6xl mx-auto py-8 px-4" dir="rtl">

<div class="flex justify-between items-start mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">

            <button wire:click="$dispatch('open-sales-form')"
                class="w-max bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إضافة عملية شراء
            </button>

        </div>


        <button wire:click="openExcelModal"
            class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
                نقل إلى اكسل
        </button>

    </div>


    <div
        class="text-sm text-gray-500 font-medium bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 mt-2">
        إجمالي العمليات: {{ $sales->total() }}
    </div>
</div>


<div x-data="{ showModal: false }" @close-calculator-modal.window="showModal = false">

    <button @click="showModal = true"
            class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
        </svg>
        Calculator
    </button>

    

    <div x-show="showModal"
         x-cloak
         x-transition.opacity.duration.300ms
         class="fixed inset-0 z-50 flex flex-col items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         dir="rtl"
         style="display: none;">

        <div class="w-full max-w-md"
             @click.away="showModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="flex justify-end mb-3">
                <button @click="showModal = false" class="text-white bg-white/20 hover:bg-red-500 rounded-full p-2 backdrop-blur-md transition-all shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <livewire:quick-calculator />

            <div class="mt-3">
                <button @click="showModal = false" class="w-full bg-white hover:bg-red-50 hover:text-red-600 border border-gray-200 text-gray-800 px-6 py-4 rounded-2xl font-extrabold transition-all shadow-lg">
                    إغلاق الحاسبة
                </button>
            </div>

        </div>
    </div>
</div>


    {{-- excel sheet  --}}
@if($showExcelModal)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-all" dir="rtl">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden flex flex-col">

        <div class="bg-green-600 p-5 flex justify-between items-center text-white">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 class="text-lg font-bold"> نقل إلى اكسل</h3>
            </div>
            <button wire:click="closeExcelModal" class="text-green-200 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 space-y-5 text-right">
            <p class="text-sm text-gray-500 mb-2">حدد الفترة الزمنية التي ترغب في تصدير بياناتها.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">من تاريخ</label>
                    <input type="date" wire:model="startDate"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-green-500 focus:border-green-500 shadow-sm transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">إلى تاريخ</label>
                    <input type="date" wire:model="endDate"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-green-500 focus:border-green-500 shadow-sm transition-all">
                </div>
            </div>

            @if($startDate || $endDate)
            <div class="flex justify-start pt-2">
                <button wire:click="$set('startDate', null); $set('endDate', null)" class="text-sm text-red-500 hover:text-red-700 font-bold flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    مسح التواريخ
                </button>
            </div>
            @endif
        </div>

        <div class="bg-gray-50 p-5 flex flex-col md:flex-row-reverse justify-start gap-3 border-t border-gray-100">

            <button wire:click="exportExcel"
                    class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    تأكيد
            </button>

            <button wire:click="closeExcelModal"
                    class="w-full md:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 px-6 py-2.5 rounded-xl font-bold transition-all shadow-sm">
                إلغاء
            </button>

        </div>

    </div>
</div>
@endif




    <div class="mb-6">
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="ابحث بالاسم، رقم الهوية..."
                class="block w-full p-3 pr-10 text-sm text-gray-900 border border-gray-200 rounded-2xl bg-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all"
            >
            @if(!empty($search))
                <button wire:click="$set('search', '')" class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-4 h-4 text-gray-400 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <h2 class="text-2xl font-black text-gray-800 underline decoration-indigo-500 underline-offset-8 mb-6 mt-4">
        سجل العمليات
    </h2>

    <div class="space-y-3">
        @forelse($sales as $sale)
            @empty
            <div class="p-10 text-center bg-white rounded-2xl border-2 border-dashed">
                لا توجد نتائج مطابقة لبحثك.
            </div>
        @endforelse
    </div>





    <div class="space-y-3">
        @forelse($sales as $sale)
            <div wire:click="openDetails({{ $sale->id }})"
                 wire:key="sale-{{ $sale->id }}"
                 class="group bg-white border border-gray-100 p-4 rounded-xl flex flex-col md:flex-row items-center justify-between gap-4 cursor-pointer hover:border-indigo-300 hover:shadow-lg transition-all duration-200">

                <div class="flex items-center gap-4 w-full md:w-1/3">
                    <div class="bg-gray-800 text-white w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm shadow-sm group-hover:bg-indigo-600 transition-colors">
                      #{{ $loop->iteration }}
                    </div>



                    <div>
                        <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $sale->full_name }}</h3>
                        <p class="text-xs text-gray-400 font-mono">{{ $sale->national_id }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-around w-full md:w-1/3 border-r border-l border-gray-50 px-4">
                    <div class="text-center">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">العيار</p>
                        <p class="text-sm font-bold text-gray-700">{{ $sale->karat }}K</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">الوزن</p>
                        <p class="text-sm font-bold text-gray-700">{{ $sale->weight }} ج</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-gray-400 font-bold uppercase">التاريخ</p>
                        <p class="text-sm font-bold text-gray-700">{{ $sale->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-6 w-full md:w-1/3">
                    <div class="text-left">
                        <p class="text-[10px] text-gray-400 font-bold">إجمالي المبلغ</p>
                        <p class="text-xl font-black text-indigo-700">{{ number_format($sale->weight * $sale->sale_price, 2) }} <span class="text-xs">SAR</span></p>
                    </div>
                    <div class="text-gray-300 group-hover:text-indigo-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </div>
                </div>
             <button wire:click.stop="delete({{ $sale->id }})"
                    wire:confirm="Are you sure you want to delete this record?"
                    class="bg-red-100 text-red-600 border border-red-200 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide hover:bg-red-600 hover:text-white transition">
               حذف
            </button>

            </div>

        @empty
            <div class="bg-white p-20 text-center rounded-3xl border-2 border-dashed border-gray-100">
                <p class="text-gray-400 font-bold">لا توجد مبيعات مسجلة حالياً</p>
            </div>
        @endforelse

    </div>

    <div class="mt-8">
        {{ $sales->links() }}
    </div>

@if($showModal && $selectedSale)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-md transition-all">
        <div class="bg-white w-full max-w-4xl rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300 flex flex-col max-h-[90vh]">

            <div class="bg-gray-800 p-6 flex justify-between items-center text-white shrink-0">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-500 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold italic">تفاصيل الفاتورة #{{ $selectedSale->id }}</h3>
                </div>

                <button wire:click="closeModal" class="bg-white/10 hover:bg-white/20 p-2 rounded-full transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-8 overflow-y-auto flex-1">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="bg-gray-50 p-5 rounded-3xl border border-gray-100">
                            <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">بيانات العميل</label>
                            <p class="text-xl font-bold text-gray-900 mb-1">{{ $selectedSale->full_name }}</p>
                            <div class="flex gap-4 text-sm text-gray-500">
                                <span><strong class="text-gray-700">رقم الهوية:</strong> {{ $selectedSale->national_id }}</span>
                                <span><strong class="text-gray-700">رقم النسخة:</strong> {{ $selectedSale->id_version_number }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-indigo-50 p-4 rounded-3xl text-center">
                                <label class="block text-[10px] font-bold text-indigo-400 mb-1">العيار</label>
                                <p class="text-2xl font-black text-indigo-700">{{ $selectedSale->karat }}<span class="text-sm">K</span></p>
                            </div>
                            <div class="bg-indigo-50 p-4 rounded-3xl text-center">
                                <label class="block text-[10px] font-bold text-indigo-400 mb-1">الوزن الصافي</label>
                                <p class="text-2xl font-black text-indigo-700">{{ $selectedSale->weight }}<span class="text-sm"> ج</span></p>
                            </div>
                        </div>

                        <div class="bg-indigo-600 p-6 rounded-3xl text-white shadow-xl shadow-indigo-100">
                            <div class="flex justify-between items-center opacity-80 mb-2">
                                <span class="text-sm">سعر الجرام اليوم: {{ number_format($selectedSale->sale_price, 2) }} ريال</span>
                            </div>
                            <label class="block text-xs font-bold mb-1">إجمالي المبلغ المدفوع</label>
                            <p class="text-4xl font-black">{{ number_format($selectedSale->weight * $selectedSale->sale_price, 2) }} <span class="text-lg">ريال</span></p>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest text-center">المرفقات وصورة المنتج</label>
                        <div class="flex-1 bg-gray-50 rounded-[2rem] border-4 border-white shadow-inner flex items-center justify-center overflow-hidden min-h-[250px]">
                            @if($selectedSale->product_image)
                                <img src="{{ asset('storage/' . $selectedSale->product_image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-10">
                                    <p class="text-gray-400 font-bold">لا يوجد صورة مرفقة</p>
                                </div>
                            @endif
                        </div>
                        <div class="mt-4 flex justify-around text-xs text-gray-400 font-bold">
                            <span>اسم المحل: {{ $selectedSale->store_name }}</span>
                            <span>بواسطة: {{ $selectedSale->employee_name }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h4 class="text-sm font-black text-gray-400 mb-4 tracking-widest uppercase">سجل كافة الطلبات ({{ $customerOrders->total() }})</h4>

                    <div class="grid grid-cols-1 gap-3">
                        @foreach($customerOrders as $order)
                            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:bg-indigo-50 transition-colors">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-800">طلب رقم #{{ $order->id }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                                </div>

                                <div class="flex gap-8 text-center">
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">الوزن</p>
                                        <p class="text-sm font-bold text-gray-700">{{ $order->weight }} ج</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">الإجمالي</p>
                                        <p class="text-sm font-black text-indigo-600">{{ number_format($order->weight * $order->sale_price, 2) }} ريال</p>
                                    </div>
                                </div>

                                <button wire:click="openDetails({{ $order->id }})" class="text-indigo-500 hover:text-indigo-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4" dir="ltr">
                        {{ $customerOrders->links() }}
                    </div>
                </div>

            </div>

            <div class="bg-gray-50 p-6 flex justify-end gap-3 shrink-0">
                <button wire:click="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-2xl font-bold transition-all">إغلاق</button>
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-2xl font-bold transition-all flex items-center gap-2 shadow-lg shadow-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    طباعة الفاتورة
                </button>
            </div>





        </div>
    </div>
@endif
<livewire:sales-form />
