<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg p-8 border border-gray-200">

        <h2 class="text-2xl font-bold text-gray-900 mb-2">Profile Information</h2>
        <p class="text-sm text-gray-500 mb-6">Update your account's profile information and email address.</p>

        @if (session()->has('message'))
            <div class="mb-6 p-4 text-green-700 bg-green-100 rounded-md border border-green-200">
                {{ session('message') }}
            </div>
        @endif

        {{-- update the name and the email --}}

        <form wire:submit.prevent="updateProfile" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center gap-4 mt-6">
                <button type="submit" class="inline-flex justify-center rounded-md bg-gray-900 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-gray-700 transition">
                    Save Changes
                </button>
            </div>
        </form>
        </div>



        {{-- Resset your password blade and php file view --}}

        <div class="bg-white shadow sm:rounded-lg p-8 border border-gray-200">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Update Password</h2>
    <p class="text-sm text-gray-500 mb-6">Ensure your account is using a long, random password to stay secure.</p>


    <form wire:submit.prevent="updatePassword" class="space-y-6">

        <div>
            <label class="block text-sm font-medium text-gray-700">Current Password</label>
            <input type="password" wire:model="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border text-gray-900">
            @error('current_password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">New Password</label>
            <input type="password" wire:model="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border text-gray-900">
            @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" wire:model="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2 border text-gray-900">
            @error('password_confirmation') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center gap-4 mt-6">
            <button type="submit" class="inline-flex justify-center rounded-md bg-gray-900 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-gray-700 transition">
                Save Password
            </button>

            <span x-data="{ shown: false, timeout: null }"
                  x-on:password-updated.window="clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);"
                  x-show="shown"
                  x-transition
                  style="display: none;"
                  class="text-sm text-green-600 font-medium">
                Saved.
            </span>
        </div>
    </form>
</div>


        {{-- delete your account --}}

    <div class="bg-white shadow sm:rounded-lg p-8 border border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Delete Your Account</h2>

           @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
            @endif
    </div>
</div>
