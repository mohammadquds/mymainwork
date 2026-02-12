<div>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <nav class="bg-gray-800 p-6 rounded-b-lg shadow-md">
        <div class="mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="/homePage" class="-m-1.5 p-1.5">
                    <img src="https://as2.ftcdn.net/jpg/04/92/02/53/1000_F_492025360_Ie3uQ8atn7SKumbIX1dj9eMJccHP8a5N.jpg" 
                         alt="Logo" class="h-12 w-auto rounded" />
                </a>
                
                <div class="flex items-center gap-4">
                    <a href="/homePage" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">Home Page</a>
                    <a href="/activity-logs" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">Activity Logs</a>
                    <a href="/subscription" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition">Subscription</a>
                </div>
            </div>

            <div>
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-300 hover:text-red-400 px-3 py-2 rounded-md text-sm font-medium transition">Logout</button>
                </form>
            </div>
        </div>    
    </nav>
    <div class="w-full px-4 py-6">
            <h1 class="text-2xl font-bold mb-4">Subscription Page</h1>
        <div class="w-full bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
            <table class="w-full text-right border-collapse">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-4 text-sm font-bold uppercase tracking-wider">الشركة</th>
                        <th class="p-4 text-sm font-bold uppercase tracking-wider">النوع</th>
                        <th class="p-4 text-sm font-bold uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="p-4 text-sm font-bold uppercase tracking-wider"> الجوال</th>
                        <th class="p-4 text-sm font-bold uppercase tracking-wider">الحالة</th>
                        <th class="p-4 text-sm font-bold uppercase tracking-wider">تاريخ البدء</th>
                        <th class="p-4 text-sm font-bold uppercase tracking-wider">تاريخ الانتهاء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subscriptions as $sub)
                    <tr class="hover:bg-indigo-50 transition-colors duration-200">
                        <td class="p-4 font-bold text-gray-800">{{ $sub->company_name }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ $sub->type }}</td>
                        <td class="p-4 text-sm text-indigo-600 font-mono">{{ $sub->email }}</td>
                        <td class="p-4 text-sm text-indigo-600 font-mono">{{ $sub->phone_number }}</td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold 
                                {{ $sub->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $sub->status == 'active' ? 'نشط' : 'منتهي' }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-500 font-mono">{{ $sub->start_date }}</td>
                        <td class="p-4 text-sm text-gray-500 font-mono">{{ $sub->end_date }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-400 font-bold">لا توجد اشتراكات مسجلة حالياً.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 px-2">
            {{ $subscriptions->links() }}
        </div>
    </div>    
</div>

