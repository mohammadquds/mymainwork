<div>
    <div class="bg-white min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-3xl p-8 border border-gray-100">
        <form wire:submit.prevent="register">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-2xl font-bold text-gray-900 leading-7">personal information</h2>
                    <p class="mt-1 text-sm text-gray-600">Please enter your company details accurately to create your subscription to the system.</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label class="block text-sm font-semibold text-gray-900">Company Name</label>
                            <div class="mt-2">
                                <input type="text" wire:model="company_name" placeholder="e.g., Advanced Solutions Company" 
                                    class="block w-full rounded-lg border-gray-300 py-2.5 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 border px-4">
                                @error('company_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <label class="block text-sm font-semibold text-gray-900">Full Name</label>
                            <div class="mt-2">
                                <input type="text" wire:model="full_name" placeholder="e.g., John Doe" 
                                    class="block w-full rounded-lg border-gray-300 py-2.5 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 border px-4">
                                @error('full_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-semibold text-gray-900">Subscription Type</label>
                            <div class="mt-2">
                                <select wire:model="type" class="block w-full rounded-lg border-gray-300 py-2.5 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 border px-4">
                                    <option value="">Select Subscription Type</option>
                                    <option value="Basic">Basic</option>
                                    <option value="Premium">Premium</option>
                                    <option value="Enterprise">Enterprise</option>
                                </select>
                                @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <label class="block text-sm font-semibold text-gray-900"></label>Email Address</label>
                            <div class="mt-2">
                                <input type="email" wire:model="email" placeholder="email@company.com" 
                                    class="block w-full rounded-lg border-gray-300 py-2.5 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 border px-4">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-semibold text-gray-900">Phone Number</label>
                            <div class="mt-2">
                                <input type="text" wire:model="phone_number" placeholder="05xxxxxxxx" 
                                    class="block w-full rounded-lg border-gray-300 py-2.5 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 border px-4">
                                @error('phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-semibold text-gray-900">Commercial Registration Number (Optional)</label>
                            <div class="mt-2">
                                <input type="text" wire:model="Commercial_Registration_Number" placeholder="1010xxxxxx" 
                                    class="block w-full rounded-lg border-gray-300 py-2.5 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 border px-4">
                                @error('Commercial_Registration_Number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-semibold text-gray-900">VAT Number (Optional)</label>
                            <div class="mt-2">
                                <input type="text" wire:model="vat_number" placeholder="3000xxxxxx" 
                                    class="block w-full rounded-lg border-gray-300 py-2.5 text-gray-900 shadow-sm focus:ring-2 focus:ring-indigo-600 border px-4">
                                @error('vat_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-x-6">
                <button type="submit" 
                    class="rounded-xl bg-blue-700 px-8 py-3 text-sm font-bold text-white shadow-lg hover:bg-indigo-500 focus-visible:outline-2 transition duration-200">
                    Create Subscription Now
                </button>
            </div>
        </form>
    </div>
</div>
</div>