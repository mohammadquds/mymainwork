<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" dir="rtl">

    {{-- HEADER & ACTIONS --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-col gap-6">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"></path>
                    </svg>
                   سجل الانشطة
                </h1>
                <p class="text-sm text-slate-500 font-medium mt-1">عدد السجلات: <span
                        class="text-amber-600 font-bold">{{ $logs->total() }}</span></p>
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="ابحث بالاسم,البريد الإلكتروني, نوع الاجراء(بالإنجليزية) ..."
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

    {{-- The Log Table --}}
    <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead class="bg-slate-100 border-b border-slate-200">
                    <tr>
                        <th class="py-4 px-6 text-sm font-bold text-slate-700">المستخدم</th>
                        <th class="py-4 px-6 text-sm font-bold text-slate-700">الإجراء</th>
                        <th class="py-4 px-6 text-sm font-bold text-slate-700">القسم / السجل</th>

                        {{-- UPDATE  Added the Details Header --}}
                        <th class="py-4 px-6 text-sm font-bold text-slate-700">التفاصيل</th>

                        <th class="py-4 px-6 text-sm font-bold text-slate-700">التاريخ والوقت</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50 transition-colors">

                            {{-- User (Causer) --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs">
                                        {{ substr($log->causer->name ?? '?', 0, 2) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-900">{{ $log->causer->name ?? 'النظام' }}</span>
                                </div>
                            </td>

                            {{-- Action Badge --}}
                            <td class="py-4 px-6">
                                @if($log->event === 'created')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">إضافة جديدة</span>
                                @elseif($log->event === 'updated')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">تعديل بيانات</span>
                                @elseif($log->event === 'deleted')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">عملية حذف</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">{{ $log->event }}</span>
                                @endif
                            </td>

                        {{-- Target (Subject) --}}
                            <td class="py-4 px-6">
                                <div class="text-sm font-bold text-slate-700">
                                    {{ $this->getModelArabicName($log->subject_type) }}
                                    <span class="text-slate-400 font-normal">#{{ $this->getSubjectIdentifier($log) }}</span>
                                </div>
                            </td>


                            {{-- UPDATE  The brand new Details Column --}}
                            <td class="py-4 px-6 text-sm text-slate-600 leading-relaxed">
                                @if($log->event === 'created')
                                    تمت إضافة {{ $this->getModelArabicName($log->subject_type) }} جديدة.

                               @elseif($log->event === 'deleted')
                                    تم حذف {{ $this->getModelArabicName($log->subject_type) }} رقم #{{ $this->getSubjectIdentifier($log) }}.

                                @elseif($log->event === 'updated')
                                    @php $changes = $this->getFormattedChanges($log); @endphp

                                    @if(count($changes) > 0)
                                        تم
                                        @foreach($changes as $change)
                                            تعديل <strong>{{ $change['label'] }}</strong>
                                            من <span class="text-amber-600 font-bold" dir="ltr">{{ $change['old'] }}</span>
                                            إلى <span class="text-green-600 font-bold" dir="ltr">{{ $change['new'] }}</span>
                                            @if(!$loop->last) ، و @endif
                                        @endforeach
                                        .
                                    @else
                                        تم تعديل بعض الحقول.
                                    @endif
                                @endif
                            </td>

                            {{-- Time --}}
                            <td class="py-4 px-6 text-sm text-slate-500" dir="ltr">
                                {{ $log->created_at->format('Y-m-d h:i A') }}

                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- UPDATE  Changed colspan to 5 --}}
                            <td colspan="5" class="py-8 text-center text-slate-500 font-medium">لا توجد نشاطات مسجلة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
