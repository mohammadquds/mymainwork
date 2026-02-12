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
        <h1 class="text-2xl font-bold mb-4">Home Page</h1>
        <p class="text-gray-700">This is the home page content.</p>
    </div>    
</div>