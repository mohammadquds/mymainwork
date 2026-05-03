<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8" dir="rtl">

    {{-- Header --}}
    <div class="mb-8 flex items-center gap-4">
        <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg">
            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <div>
            <h2 class="text-2xl font-black text-slate-900">سجل النشاطات (Activity Log)</h2>
            <p class="text-sm text-slate-500 mt-1">مراقبة كافة التعديلات، الإضافات، وعمليات الحذف في النظام.</p>
        </div>
    </div>

    {{-- The Log Table --}}
    <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="py-4 px-6 text-sm font-bold text-slate-700">المستخدم</th>
                        <th class="py-4 px-6 text-sm font-bold text-slate-700">الإجراء</th>
                        <th class="py-4 px-6 text-sm font-bold text-slate-700">القسم / السجل</th>

                        {{-- UPDATE 1: Added the Details Header --}}
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

                                    {{-- UPDATE 2: Cleaned up the translation using the backend function --}}
                                    {{ $this->getModelArabicName($log->subject_type) }}

                                    <span class="text-slate-400 font-normal">#{{ $log->subject_id }}</span>
                                </div>
                            </td>

                            {{-- UPDATE 3: The brand new Details Column --}}
                            <td class="py-4 px-6 text-sm text-slate-600 leading-relaxed">
                                @if($log->event === 'created')
                                    تمت إضافة {{ $this->getModelArabicName($log->subject_type) }} جديدة.

                                @elseif($log->event === 'deleted')
                                    تم حذف {{ $this->getModelArabicName($log->subject_type) }} رقم #{{ $log->subject_id }}.

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
                            {{-- UPDATE 4: Changed colspan to 5 --}}
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
