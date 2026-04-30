<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" dir="rtl">

    {{-- HEADER & ACTIONS --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-col gap-6">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"></path>
                    </svg>
                    إدارة السجلات
                </h1>
                <p class="text-sm text-slate-500 font-medium mt-1">إجمالي السجلات: <span
                        class="text-amber-600 font-bold">{{ $activity_logs->total() }}</span></p>
            </div>

            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                {{-- <button @click="$dispatch('open-calculator-modal')"
                    class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 1１h.０１M１２ １１h．０１M９ １１h．０１M７ ２１h１０a２ ２ ０ ００２－２V５a２ ２ ０ ００－２－２H７a２ ２ ０ ００－２ ２v１４a２ ２ ０ ００２ ２z">
                        </path>
                    </svg>
                    الحاسبة
                </button> --}}


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
            {{-- @if(!empty($search))
                <button wire:click="$set('search', '')" class="absolute inset-y-0 left-0 flex items-center pl-3 pt-6">
                    <svg class="w-4 h-4 text-slate-400 hover:text-red-500 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            @endif --}}
        </div>
    </div>    
        <div class="w-full bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-start text-sm font-bold uppercase tracking-wider">المستخدم</th>
                        <th class="px-6 py-4 text-start text-sm font-bold uppercase tracking-wider">الدور</th>
                        <th class="px-6 py-4 text-start text-sm font-bold uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="px-6 py-4 text-start text-sm font-bold uppercase tracking-wider">نوع النشاط</th>
                        <th class="px-6 py-4 text-start text-sm font-bold uppercase tracking-wider">القسم</th>
                        <th class="px-6 py-4 text-start text-sm font-bold uppercase tracking-wider">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($activity_logs as $log)
                    <tr class="hover:bg-blue-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->user->name ?? 'غير معروف' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-semibold">{{ $log->user_role }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-mono text-xs">{{ $log->user_email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 {{ $log->activity_type == 'تسجيل دخول' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-lg text-xs font-bold">
                                {{ $log->activity_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->section }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono text-xs">{{ $log->date }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 px-2">
            {{ $activity_logs->links() }}
        </div>
    </div>
</div>
