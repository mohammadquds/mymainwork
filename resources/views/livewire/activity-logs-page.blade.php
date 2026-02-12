<div>    
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
        <h1 class="text-2xl font-bold mb-4">Activity Logs</h1>
        
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