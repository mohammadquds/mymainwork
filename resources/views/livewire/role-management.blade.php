<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" dir="rtl">

    {{-- HEADER & ACTIONS --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-col gap-6">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                    <svg class="w-8 h-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 inline-flex mx-1">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"></path>
                    </svg>            
                    إدارة الصلاحيات
                </h1>
                <p class="text-sm text-slate-500 font-medium mt-1">عدد الصلاحيات: <span
                        class="text-amber-600 font-bold">{{ $roles->total() }}</span></p>
            </div>

            <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                @can('role.create')
                    <button wire:click="create" class="bg-amber-500 text-slate-900 px-6 py-2.5 rounded-xl font-bold hover:bg-amber-400 transition-all shadow-lg shadow-amber-100 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z"></path>
                    </svg>
                        إضافة صلاحية جديدة
                    </button>
                @endcan


                {{-- <button wire:click="$dispatch('open-sales-form')"
                    class="w-full sm:w-auto bg-slate-900 hover:bg-black text-amber-400 px-6 py-2.5 rounded-xl font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    عملية جديدة
                </button> --}}
            </div>
        </div>
    </div>

        @if (session()->has('message'))
            <div
                class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow overflow-x-auto sm:rounded-lg">

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-start text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">
                            اسم الصلاحية
                        </th>
                        <th class="px-6 py-3 text-start text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">
                            الأذونات</th>
                        <th class="px-6 py-3 text-start text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">
                            عدد المستخدمين
                        </th>
                        <th class="px-6 py-3 text-start text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">
                            الإعدادات</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-white divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($roles as $role)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-700">
                                {{ $role->name }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-200 rounded-full">
                                        {{ $role->permissions->count() }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-700">
                                {{ $role->users->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @can('role.edit')
                                    <button wire:click="edit({{ $role->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-2">
                                        تعديل
                                    </button>
                                @endcan
                                @can('role.delete')
                                    @if ($role->users->count() == 0)
                                        <button wire:click="delete({{ $role->id }})" wire:confirm="Are you sure?"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">
                                            حذف
                                        </button>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-gray-900 dark:text-white">
            {{ $roles->links() }}
        </div>

        <!-- Modal for mobile view -->
        @if ($showModal)
            <div class="fixed inset-0  backdrop-blur-md overflow-y-auto h-full w-full">

                <div
                    class="relative mt-10 mx-auto p-5 border w-10/12 sm:w-3/4 md:w-2/3 max-w-4xl max-h-[85vh] overflow-y-auto shadow-lg rounded-md bg-white dark:bg-gray-800">

                    <div class="mt-3">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            {{ $isEditing ? 'Edit Role' : 'إضافة صلاحية' }}
                        </h3>

                        <form wire:submit.prevent="save">
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">اسم
                                    الصلاحية</label>
                                <input type="text" wire:model="name"
                                    class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">الأذونات</label>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach ($permissions as $group => $groupPermissions)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                            <h4 class="font-medium text-gray-900 dark:text-white mb-2 capitalize">
                                                {{ $group }}</h4>
                                            @foreach ($groupPermissions as $permission)
                                                <div class="flex items-center mb-2">
                                                    <input type="checkbox" wire:model="selectedPermissions"
                                                        value="{{ $permission->name }}"
                                                        id="permission-{{ $permission->id }}" class="mr-2">
                                                    <label for="permission-{{ $permission->id }}"
                                                        class="text-sm text-gray-700 dark:text-gray-300">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" wire:click="closeModal"
                                    class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded">
                                    الغاء
                                </button>
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded">
                                    حفظ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
