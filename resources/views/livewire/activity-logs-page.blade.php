<div>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <div class="container">
        <h1 class="text-2xl font-bold mb-4">Activity Logs</h1>
        <div class="w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-100">                                    
                    <th class="px-6 py-3 text-start text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ __('User') }}</th>
                    <th class="px-6 py-3 text-start text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ __('Role') }}</th>
                    <th class="px-6 py-3 text-start text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ __('Email') }}</th>
                    <th class="px-6 py-3 text-start text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ __('Description') }}</th>
                    <th class="px-6 py-3 text-start text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ __('Activity Type') }}</th>
                    <th class="px-6 py-3 text-start text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ __('Section') }}</th>
                    <th class="px-6 py-3 text-start text-sm font-semibold text-gray-700 uppercase tracking-wider">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($activity_logs as $log)
                    <tr>
                       <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $log->user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $log->user_role }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $log->user_email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $log->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $log->activity_type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $log->section }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $activity_logs->links() }}
        </div>
    </div>
</div>
