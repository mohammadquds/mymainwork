<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            {{ $isLoginMode ? 'Create your account' : 'Login to your account' }}
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl border border-gray-100 sm:rounded-2xl sm:px-10">

            @if(!$isLoginMode)
                <form wire:submit.prevent="loginUser" wire:key="login-form" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email address</label>
                        <input wire:model="log_email" type="email" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('log_email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input wire:model="log_password" type="password" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('log_password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center">
                        <input wire:model="remember" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>


                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                        Login
                    </button>
                </form>

            @else
             <form wire:submit.prevent="registerUser" wire:key="reg-form" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input wire:model="name" type="text" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email address</label>
                        <input wire:model="sign_email" type="email" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('sign_email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>


                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Company Name </label>
                        <input
                            wire:model="company_name" type="text"
                            @if($isCompanyLocked)
                                readonly
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-gray-500 cursor-not-allowed focus:outline-none"
                            @else
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-800 focus:ring-2 focus:ring-blue-500 transition"
                            @endif
                            >
                        @error('company_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>



                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input wire:model="sign_password" type="password" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        @error('sign_password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input wire:model="sign_password_confirmation" type="password" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                        Sign up
                    </button>
                </form>

              

            @endif

            <div class="mt-6 text-center">
                <button type="button" wire:click="toggleMode" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                    {{ $isLoginMode ? "Already have an account? Log in" : "Don't have an account? Sign up"  }}
                </button>
            </div>
        </div>
    </div>
</div>
