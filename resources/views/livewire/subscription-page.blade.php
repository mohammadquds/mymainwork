<div class="w-full px-4 py-6  text-right border-collapse whitespace-nowrap" dir="rtl">
    <h1 class="text-2xl text-gray-700 font-bold mb-4 text-right border-collapse" dir="rtl">صفحة الإشتراكات</h1>
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
