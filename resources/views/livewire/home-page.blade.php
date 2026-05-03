<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" dir="rtl">

    {{-- HEADER & ACTIONS --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-col gap-6">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    إدارة المبيعات
                </h1>
                <p class="text-sm text-slate-500 font-medium mt-1">إجمالي العمليات المسجلة: <span
                        class="text-amber-600 font-bold">{{ $sales->total() }}</span></p>
            </div>

            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                <button @click="$dispatch('open-calculator-modal')"
                    class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    الحاسبة
                </button>


                <button wire:click="$dispatch('open-sales-form')"
                    class="w-full sm:w-auto bg-slate-900 hover:bg-black text-amber-400 px-6 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    عملية جديدة
                </button>
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


    {{-- SALES LIST --}}
    <div class="space-y-3">
        @forelse($sales as $sale)
            <div wire:click="openDetails({{ $sale->id }})" wire:key="sale-{{ $sale->id }}"
                class="group bg-white border border-slate-200 p-4 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-4 cursor-pointer hover:border-amber-400 hover:shadow-md transition-all duration-200">

                <div class="flex items-center gap-4 w-full md:w-1/3">
                    <div
                        class="bg-slate-50 border border-slate-100 text-slate-500 w-12 h-12 rounded-xl flex items-center justify-center font-black shadow-sm group-hover:bg-amber-100 group-hover:text-amber-700 group-hover:border-amber-200 transition-colors">
                        #{{ $loop->iteration }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 group-hover:text-amber-600 transition-colors">
                            {{ $sale->full_name }}</h3>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $sale->national_id }}</p>
                    </div>
                </div>

                <div
                    class="flex items-center justify-around w-full md:w-1/3 md:border-r md:border-l border-slate-100 px-4 py-2 md:py-0 bg-slate-50 md:bg-transparent rounded-xl md:rounded-none">
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">العيار</p>
                        <p class="text-sm font-bold text-slate-800">{{ $sale->karat }}<span
                                class="text-xs text-amber-500 ml-0.5">K</span></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">الوزن</p>
                        <p class="text-sm font-bold text-slate-800">{{ $sale->weight }}<span
                                class="text-xs text-amber-500 ml-0.5">g</span></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">التاريخ</p>
                        <p class="text-sm font-bold text-slate-800">{{ $sale->created_at->format('Y-m-d') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-1/3">
                    <div class="text-right">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">المبلغ الإجمالي</p>
                        <p class="text-xl font-black text-slate-900">
                            {{ number_format($sale->weight * $sale->sale_price, 2) }} <span
                                class="text-xs text-slate-400">SAR</span></p>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- زر التعديل  --}}
                        <button wire:click.stop="editSale({{ $sale->id }})" 
                            class="text-slate-300 hover:text-indigo-600 bg-slate-50 hover:bg-indigo-50 p-2 rounded-lg transition-colors border border-transparent hover:border-indigo-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>

                        <button wire:click.stop="delete({{ $sale->id }})" wire:confirm="تأكيد حذف هذه العملية؟"
                            class="text-slate-300 hover:text-red-500 bg-slate-50 hover:bg-red-50 p-2 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="bg-white py-16 text-center rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                </div>
                <p class="text-slate-500 font-bold text-lg">لا توجد مبيعات مسجلة</p>
                <p class="text-slate-400 text-sm mt-1">قم بإضافة عملية شراء جديدة للبدء</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8" dir="ltr">
        {{ $sales->links() }}
    </div>


    {{-- زر التعديل --}}
    @if($isEditMode)
        <div class="fixed inset-0 z-[150] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all" dir="rtl">
            <div class="bg-white w-full max-w-md max-h-[90vh] rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col animate-in zoom-in-95 duration-200">
                
                {{-- الهيدر --}}
                <div class="bg-slate-900 p-5 flex justify-between items-center text-white shrink-0">
                    <h3 class="text-lg font-black">تعديل بيانات العملية الشامل</h3>
                    <button wire:click="closeEditModal" class="text-slate-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- جسم الفورم (قابل للتمرير) --}}
                <form wire:submit.prevent="updateSale" class="flex-1 overflow-y-auto p-6 space-y-5 bg-slate-50/30 custom-scrollbar">
                    
                    {{-- البيانات الأساسية --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase">اسم العميل الكامل</label>
                            <input type="text" wire:model="editingSale.full_name" class="w-full bg-white border border-slate-200 rounded-2xl p-3 font-bold shadow-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase">رقم الهوية</label>
                                <input type="text" wire:model="editingSale.national_id" class="w-full bg-white border border-slate-200 rounded-2xl p-3 font-bold shadow-sm font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase">نسخة الهوية</label>
                                <input type="text" wire:model="editingSale.id_version_number" class="w-full bg-white border border-slate-200 rounded-2xl p-3 font-bold shadow-sm font-mono text-sm">
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100">

                    {{-- بيانات المنتج --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase">العيار</label>
                            <select wire:model="editingSale.karat" class="w-full bg-white border border-slate-200 rounded-2xl p-3 font-bold shadow-sm">
                                <option value="18">18K</option>
                                <option value="21">21K</option>
                                <option value="22">22K</option>
                                <option value="24">24K</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase">الوزن (جرام)</label>
                            <input type="number" step="0.01" wire:model="editingSale.weight" class="w-full bg-white border border-slate-200 rounded-2xl p-3 font-bold shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase">سعر الجرام (SAR)</label>
                        <input type="number" step="0.01" wire:model="editingSale.sale_price" class="w-full bg-white border border-slate-200 rounded-2xl p-3 font-bold shadow-sm">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase">نوع الوحدة</label>
                        <select wire:model="editingSale.unit_type" class="w-full bg-white border border-slate-200 rounded-2xl p-3 font-bold shadow-sm">
                            <option value="خاتم">خاتم</option>
                            <option value="حلق">حلق</option>
                            <option value="سلسال">سلسال</option>
                            <option value="اسواره">اسواره</option>
                            <option value="سبيكه">سبيكه</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-bold text-slate-700 mb-1">الوصف (اختياري)</label>
                        <textarea wire:model="editingSale.description" rows="3"
                            class="w-full border border-slate-300 rounded-xl shadow-sm px-3 py-2.5 resize-none focus:border-amber-500 focus:ring-amber-500"></textarea>
                    </div>

                    {{-- الصور --}}
                    <div class="pt-2">
                        <label class="block text-[11px] font-black text-slate-500 uppercase mb-2">تحديث الصورة المرفقة</label>
                        <input type="file" wire:model="newPhoto" class="w-full text-xs text-slate-400 file:bg-indigo-50 file:rounded-xl file:border-0 file:px-4 file:py-2 file:font-bold">
                        @if($editingSale['product_image'])
                            <div class="mt-4 flex justify-center">
                                <img src="{{ asset('storage/' . $editingSale['product_image']) }}" class="w-24 h-24 rounded-2xl object-cover border-2 border-slate-200 shadow-sm">
                            </div>
                        @endif
                    </div>
                </form>
                <div class="text-sm text-slate-500 font-bold">
                    تاريخ الإنشاء: 
                    <span class="text-slate-900 font-mono">
                        {{ \Carbon\Carbon::parse($editingSale['created_at'])->format('Y-m-d H:i') }}
                    </span>
                </div>
                <div class="text-sm text-slate-500 font-bold">
                    تاريخ آخر تعديل: 
                    <span class="text-slate-900 font-mono">
                        {{ \Carbon\Carbon::parse($editingSale['updated_at'])->format('Y-m-d H:i') }}
                    </span>
                </div>
                {{-- الفوتر الثابت --}}
                <div class="p-6 bg-white border-t border-slate-100 flex gap-3 shrink-0">
                    <button wire:click="updateSale" class="flex-1 bg-slate-900 text-amber-400 py-4 rounded-2xl font-black shadow-lg hover:bg-black transition-all">تحديث كافة البيانات</button>
                    <button wire:click="closeEditModal" class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-2xl font-bold hover:bg-slate-200 transition-all">إلغاء</button>
                </div>
            </div>
        </div>
    @endif


    {{-- CALCULATOR --}}
    <div x-data="{ showModal: false }" @open-calculator-modal.window="showModal = true"
        @close-calculator-modal.window="showModal = false">
        <div x-show="showModal" x-cloak style="display: none;"
            class="fixed inset-0 z-[60] flex flex-col items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            dir="rtl">
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden" @click.away="showModal = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="bg-slate-900 p-4 flex justify-between items-center text-white">
                    <span class="font-bold text-amber-400">الحاسبة السريعة</span>
                    <button @click="showModal = false" class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <livewire:quick-calculator />
                </div>
            </div>
        </div>
    </div>


    {{-- EXCEL --}}
    @if($showExcelModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all"
            dir="rtl">
            <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden flex flex-col">
                <div class="bg-slate-900 p-5 flex justify-between items-center text-white">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-800 rounded-lg text-green-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">تصدير إلى Excel</h3>
                    </div>
                    <button wire:click="closeExcelModal" class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <p class="text-sm text-slate-500">حدد الفترة الزمنية التي ترغب في تصدير بياناتها.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">من تاريخ</label>
                            <input type="date" wire:model="startDate"
                                class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm transition-all bg-slate-50">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">إلى تاريخ</label>
                            <input type="date" wire:model="endDate"
                                class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm transition-all bg-slate-50">
                        </div>
                    </div>
                    @if($startDate || $endDate)
                        <button wire:click="$set('startDate', null); $set('endDate', null)"
                            class="text-xs text-red-500 hover:text-red-700 font-bold flex items-center gap-1 transition-colors mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            مسح التواريخ
                        </button>
                    @endif
                </div>

                <div
                    class="bg-slate-50 p-6 flex flex-col md:flex-row-reverse justify-start gap-3 border-t border-slate-100">
                    <button wire:click="exportExcel"
                        class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-md transition-all">تأكيد
                        التصدير</button>
                    <button wire:click="closeExcelModal"
                        class="w-full md:w-auto bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-6 py-2.5 rounded-xl font-bold transition-all shadow-sm">إلغاء</button>
                </div>
            </div>
        </div>
    @endif

    {{-- PDF --}}
    @if($showPdfModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all"
            dir="rtl">
            <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden flex flex-col">
                <div class="bg-slate-900 p-5 flex justify-between items-center text-white">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-800 rounded-lg text-red-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-white">تصدير إلى PDF</h3>
                    </div>
                    <button wire:click="closePdfModal" class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <p class="text-sm text-slate-500">حدد الفترة الزمنية التي ترغب في تصدير بياناتها.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">من تاريخ</label>
                            <input type="date" wire:model="startDate"
                                class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm transition-all bg-slate-50">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">إلى تاريخ</label>
                            <input type="date" wire:model="endDate"
                                class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm transition-all bg-slate-50">
                        </div>
                    </div>
                    @if($startDate || $endDate)
                        <button wire:click="$set('startDate', null); $set('endDate', null)"
                            class="text-xs text-red-500 hover:text-red-700 font-bold flex items-center gap-1 transition-colors mt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            مسح التواريخ
                        </button>
                    @endif
                </div>

                <div
                    class="bg-slate-50 p-6 flex flex-col md:flex-row-reverse justify-start gap-3 border-t border-slate-100">
                    <button wire:click="exportPdf"
                        class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white px-8 py-2.5 rounded-xl font-bold shadow-md transition-all">تأكيد
                        التصدير</button>
                    <button wire:click="closePdfModal"
                        class="w-full md:w-auto bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-6 py-2.5 rounded-xl font-bold transition-all shadow-sm">إلغاء</button>
                </div>
            </div>
        </div>
    @endif
    {{-- INVOICE DETAILS --}}
    @if($showModal && $selectedSale)
        <div wire:key="invoice-modal-{{ $selectedSale->id }}"
            class="fixed inset-0 z-[100] bg-slate-900/80 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6"
            dir="rtl">

            <div class="bg-slate-50 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden relative grid"
                style="height: 85dvh; max-height: 800px; grid-template-rows: auto minmax(0, 1fr) auto;">

                {{-- HEADER --}}
                <div
                    class="bg-slate-900 p-4 flex justify-between items-center text-white border-b border-amber-500/20 z-20">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-slate-800 rounded-lg text-amber-400 border border-slate-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-black text-white">فاتورة <span
                                class="text-amber-400 font-mono">#{{ $selectedSale->invoice_number }}</span></h3>
                    </div>
                    <button wire:click="closeModal"
                        class="text-slate-400 hover:text-white bg-slate-800 p-1.5 rounded-full border border-slate-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="overflow-y-auto w-full p-4 bg-slate-50/50 min-h-0" style="-webkit-overflow-scrolling: touch;">

                    {{-- Client Info --}}
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm mb-3 text-center">
                        <p class="text-sm font-black text-slate-900 mb-1">{{ $selectedSale->full_name }}</p>
                        <div class="flex flex-col gap-1 text-[10px] text-slate-500">
                            <span><strong class="text-slate-400">الهوية:</strong> <span
                                    class="font-mono">{{ $selectedSale->national_id }}</span></span>
                            <span><strong class="text-slate-400">النسخة:</strong> <span
                                    class="font-mono">{{ $selectedSale->id_version_number }}</span></span>
                        </div>
                    </div>

                    {{-- Main Receipt Data --}}
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm mb-3">
                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                            <span class="font-bold text-xs text-slate-500">العيار</span>
                            <div class="font-black text-slate-900 text-sm">{{ $selectedSale->karat }} <span
                                    class="text-[9px] text-amber-500">K</span></div>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                            <span class="font-bold text-xs text-slate-500">الوزن الصافي</span>
                            <div class="font-black text-slate-900 text-sm">{{ $selectedSale->weight }} <span
                                    class="text-[9px] text-amber-500">g</span></div>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                            <span class="font-bold text-xs text-slate-500">سعر الجرام</span>
                            <div class="font-black text-slate-900 text-sm">{{ number_format($selectedSale->sale_price, 2) }}
                                <span class="text-[9px] text-slate-400">SAR</span></div>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                            <span class="font-bold text-xs text-slate-500">نوع المنتج</span>
                            <div class="font-black text-slate-900 text-sm">{{ $selectedSale->unit_type }}</div>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-100">
                            <span class="font-bold text-xs text-slate-500">الوصف</span>
                            <div class="font-black text-slate-900 text-sm">{{ $selectedSale->description }}</div>
                        </div>

                        {{-- Image Viewer --}}
                        <div class="flex items-center justify-between py-2" x-data="{ showImageModal: false }">
                            <span class="font-bold text-xs text-slate-500">المرفقات</span>
                            @if($selectedSale->product_image)
                                <button @click="showImageModal = true"
                                    class="text-[10px] bg-amber-50 text-amber-600 px-2 py-1 rounded font-bold border border-amber-100">عرض
                                    الصورة</button>

                                {{-- THE FIX: Removed x-teleport to stop the massive Livewire memory leak --}}
                                <div x-show="showImageModal" style="display: none;"
                                    class="fixed inset-0 z-[99999] flex items-center justify-center bg-slate-900/90 p-4">
                                    <div class="relative bg-white p-2 rounded-xl shadow-2xl"
                                        @click.away="showImageModal = false" style="width: 100%; max-width: 300px;">
                                        <button @click="showImageModal = false"
                                            class="absolute -top-3 -right-3 bg-red-500 text-white p-1.5 rounded-full shadow-lg border-2 border-white">
                                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <img src="{{ asset('storage/' . $selectedSale->product_image) }}"
                                            class="w-full rounded-lg bg-slate-100"
                                            style="max-height: 300px; object-fit: contain;">
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
                        <div class="font-black text-3xl text-slate-900 mb-3">
                            {{ number_format($selectedSale->weight * $selectedSale->sale_price, 2) }} <span
                                class="text-xs text-amber-500">ريال</span></div>
                        <div class="border-t border-slate-100 pt-3 grid grid-cols-2 gap-2">
                            <div><span class="block text-[8px] text-slate-400">اسم المحل</span><span
                                    class="block text-[10px] font-black text-slate-700 truncate">{{ $selectedSale->store_name }}</span>
                            </div>
                            <div class="border-r border-slate-100"><span
                                    class="block text-[8px] text-slate-400">الموظف</span><span
                                    class="block text-[10px] font-black text-slate-700 truncate">{{ $selectedSale->employee_name }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Previous Orders --}}
                    <div class="w-full border-t border-slate-200 pt-3 pb-2">
                        <h4 class="text-xs font-black text-slate-900 mb-2 flex items-center gap-1.5">
                            <div class="p-1 bg-amber-100 text-amber-600 rounded"><svg class="w-3.5 h-3.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg></div>
                            سجل طلبات العميل <span class="text-amber-500">({{ $customerOrders->total() }})</span>
                        </h4>
                        <div class="space-y-2">
                            @foreach($customerOrders as $order)
                                <button type="button" wire:click="openDetails({{ $order->id }})"
                                    class="w-full bg-white p-2.5 rounded-lg border border-slate-200 shadow-sm flex justify-between items-center hover:border-amber-400 transition-colors focus:outline-none">
                                    <div class="text-right">
                                        <span class="text-[11px] font-black text-slate-800 block">طلب <span
                                                class="text-amber-600">#{{ $order->invoice_number }}</span></span>
                                    </div>
                                    <div class="flex items-center gap-3 text-left">
                                        <div>
                                            <p class="text-[8px] text-slate-400">الوزن</p>
                                            <p class="text-[11px] font-black text-slate-700">{{ $order->weight }} ج</p>
                                        </div>
                                        <div class="border-r border-slate-100 pr-2">
                                            <p class="text-[8px] text-slate-400">الإجمالي</p>
                                            <p class="text-[11px] font-black text-slate-900">
                                                {{ number_format($order->weight * $order->sale_price, 2) }}</p>
                                         </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        <div class="mt-3" dir="ltr">{{ $customerOrders->links() }}</div>
                    </div>

                </div>

                {{--FOOTER --}}
                <div class="bg-white p-3 sm:p-4 flex gap-2 sm:gap-3 items-center border-t border-slate-200 z-20">
                    <button wire:click="closeModal"
                        class="flex-1 bg-slate-100 text-slate-700 py-2.5 rounded-lg text-sm font-bold border border-slate-200 shadow-sm hover:bg-slate-200 transition-colors">إغلاق</button>
                    {{-- Changed from a <button> to an <a> tag. target="_blank" guarantees it opens in a new tab --}}
                            <a href="{{ route('invoice.pdf', $selectedSale->id) }}" target="_blank"
                                class="flex-1 bg-slate-900 text-amber-400 py-2.5 rounded-lg text-sm font-bold flex justify-center items-center gap-1.5 shadow-md hover:bg-black transition-colors">
                                <svg style="width: 16px; height: 16px; flex-shrink: 0;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                عرض PDF
                            </a>
                </div>

            </div>
        </div>
    @endif

    <livewire:sales-form />


    {{-- the pop up of the vat and company number--}}
    @if($showOnboardingModal)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" dir="rtl">
            <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden relative animate-in zoom-in-95 duration-300">

                {{--  CLOSE BUTTON (X) --}}
                <button wire:click="closeOnboardingModal" class="absolute top-4 left-4 text-slate-400 hover:text-white z-10 p-2 bg-slate-800/40 hover:bg-slate-700 rounded-full transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                {{-- Header --}}
                <div class="bg-slate-900 p-6 pt-8 text-center border-b border-amber-500/20 relative">
                    <div class="w-16 h-16 bg-amber-500 rounded-full mx-auto flex items-center justify-center mb-4 shadow-lg shadow-amber-500/30">
                        <svg class="w-8 h-8 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-white mb-1">تذكير بإكمال البيانات</h3>
                    <p class="text-sm text-slate-400">لإصدار الفواتير بشكل قانوني، يرجى إكمال بيانات منشأتك. يمكنك تجاوز هذه الخطوة مؤقتاً.</p>
                </div>

                {{-- Form --}}
                <form wire:submit.prevent="saveCompanyDetails" class="p-6 space-y-5">

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">  الرقم الموحد\ رقم السجل التجاري <span class="text-red-500">*</span></label>
                        <input wire:model="official_company_number" type="text" class="block w-full px-4 py-3 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 transition-all sm:text-sm text-left" dir="ltr" placeholder="مثال: 1010123456">
                        @error('official_company_number') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">الرقم الضريبي (VAT) <span class="text-red-500">*</span></label>
                        <input wire:model="vat_number" type="text" class="block w-full px-4 py-3 bg-slate-50 border-0 ring-1 ring-inset ring-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-inset focus:ring-amber-500 transition-all sm:text-sm text-left" dir="ltr" placeholder="مثال: 300012345600003">
                        @error('vat_number') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" wire:click="closeOnboardingModal" class="w-1/3 py-3.5 px-4 rounded-xl text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-all">
                            لاحقاً
                        </button>
                        <button type="submit" class="w-2/3 py-3.5 px-4 rounded-xl text-sm font-black text-slate-900 bg-amber-500 hover:bg-amber-400 shadow-lg shadow-amber-500/30 transition-all">
                            حفظ البيانات
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif
</div>