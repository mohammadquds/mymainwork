<div class="p-6  text-right border-collapse whitespace-nowrap" dir="rtl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold whitespace-normal">لوحة تحكم المستخدم </h2>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            @can('user.create')
                <button wire:click="create"
                    class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded">
                    إضافة مستخدم جديد
                </button>
            @endcan

            <button wire:click="$set('showInviteModal', true)"
                class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-4 py-2 rounded">
                دعوة مستخدم جديد عبر البريد الإلكتروني
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div
            class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

 <div class="bg-gray-100 dark:bg-gray-800 shadow overflow-x-auto sm:rounded-lg ">

        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 whitespace-nowrap text-right border-collapse whitespace-nowrap" dir ="rtl">
            <thead class="">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">الإسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">البريد الإلكتروني</th>
                    <th class="hidden md:table-cell px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">الصلاحية </th>
                    <th class="hidden md:table-cell px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">المشرف</th>
                    @can('user.edit')
                    <th class="hidden md:table-cell  px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"> الإعدادات</th>
                    @endcan
                 @can('subscription.unactive.view')  <th class="hidden md:table-cell px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase"> الإشتراك</th>@endcan
                </tr>
            </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($users as $user)

                    @if($user->hasRole('Admin') && $user->children && $user->children->count() > 0)

                        <tbody x-data="{ expanded: false }" class="divide-y divide-gray-200 dark:divide-gray-700">

                            <tr wire:click="openDetails({{ $user->id }})" class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ $user->id === auth()->id() ? 'bg-indigo-50/50 dark:bg-indigo-900/20' : '' }}">

                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white flex items-center gap-2">
                                    <button @click.stop="expanded = !expanded" class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-600">
                                        <svg :class="expanded ? 'rotate-180 text-indigo-500' : 'text-gray-400'" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>

                                    {{ $user->name }}

                                    @if($user->id === auth()->id())
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">(You)</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ $user->email }}</td>

                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex px-2 py-1 text-xs bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-white rounded-full">{{ $role->name }}</span>
                                    @endforeach
                                </td>

                                <td class="hidden md:table-cell flex items-center gap-2">
                                    @if($user->manager)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-gray-100 text-gray-800">{{ $user->manager->name }}</span>
                                    @else
                                        <span class="text-xs text-indigo-400 font-bold">Super Admin</span>
                                    @endif
                                </td>

                                <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @can('user.edit')
                                        <button wire:click.stop="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-2">تعديل</button>
                                    @endcan
                                    @can('user.delete')
                                        | <button wire:click.stop="delete({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">حذف</button>
                                    @endcan
                                </td>

                                <td>
                                    <div class="hidden md:table-cell flex items-center gap-2">
                                        @can('subscription.view')
                                            <input type="date" onclick="event.stopPropagation()" wire:model="selectedDates.{{ $user->id }}" class="border rounded px-2 py-1 text-sm text-black">
                                            <button wire:click.stop="grantAccess({{ $user->id }})" class="bg-green-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-green-700">تفعيل</button>
                                        @endcan
                                        @can('subscription.unactive.view')
                                            <button wire:click.stop="cancelSubscription({{ $user->id }})" wire:confirm="هل أنت متأكد من إغلاق النظام عن هذا المستخدم؟" class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-red-800" >إلغاء التفعيل</button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            @foreach($user->children as $child)
                                <tr x-show="expanded" x-cloak style="display: none;" wire:click="openDetails({{ $child->id }})" class="cursor-pointer bg-gray-50/80 dark:bg-gray-800/80 hover:bg-gray-100 dark:hover:bg-gray-700 border-r-4 border-indigo-400">

                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300 pr-12">
                                         {{ $child->name }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ $child->email }}</td>

                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                                        @foreach($child->roles as $role)
                                            <span class="inline-flex px-2 py-1 text-xs bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-full">{{ $role->name }}</span>
                                        @endforeach
                                    </td>

                                    <td class="hidden md:table-cell flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-gray-100 text-gray-800">{{ $user->name }}</span>
                                    </td>

                                    <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @can('user.edit')
                                            <button wire:click.stop="edit({{ $child->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-2">تعديل</button>
                                        @endcan
                                        @can('user.delete')
                                            | <button wire:click.stop="delete({{ $child->id }})" wire:confirm="Are you sure you want to delete this user?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">حذف</button>
                                        @endcan
                                    </td>

                                    <td>
                                        <div class="hidden md:table-cell flex items-center gap-2">
                                            @can('subscription.view')
                                                <input type="date" onclick="event.stopPropagation()" wire:model="selectedDates.{{ $child->id }}" class="border rounded px-2 py-1 text-sm text-black">
                                                <button wire:click.stop="grantAccess({{ $child->id }})" class="bg-green-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-green-700">تفعيل</button>
                                            @endcan
                                            @can('subscription.unactive.view')
                                                <button wire:click.stop="cancelSubscription({{ $child->id }})" wire:confirm="هل أنت متأكد من إغلاق النظام عن هذا المستخدم؟" class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-red-800" >إلغاء التفعيل</button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    @else

                        <tr wire:click="openDetails({{ $user->id }})" class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 {{ $user->id === auth()->id() ? 'bg-indigo-50/50 dark:bg-indigo-900/20' : '' }}">

                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">
                                      (You)
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-white">{{ $user->email }}</td>

                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex px-2 py-1 text-xs bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-white rounded-full">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>

                            <td class="hidden md:table-cell flex items-center gap-2">
                                @if($user->manager)
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-gray-100 text-gray-800">
                                        {{ $user->manager->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-indigo-400 font-bold">Super Admin</span>
                                @endif
                            </td>

                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @can('user.edit')
                                    <button wire:click.stop="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-2">
                                        تعديل
                                    </button>
                                @endcan
                                @can('user.delete')
                                    |
                                    <button wire:click.stop="delete({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">
                                        حذف
                                    </button>
                                @endcan
                            </td>

                            <td>
                                <div class="hidden md:table-cell flex items-center gap-2">
                                    @can('subscription.view')
                                        <input type="date" onclick="event.stopPropagation()" wire:model="selectedDates.{{ $user->id }}" class="border rounded px-2 py-1 text-sm text-black">
                                        <button wire:click.stop="grantAccess({{ $user->id }})" class="bg-green-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-green-700">تفعيل</button>
                                    @endcan
                                    @can('subscription.unactive.view')
                                        <button wire:click.stop="cancelSubscription({{ $user->id }})" wire:confirm="هل أنت متأكد من إغلاق النظام عن هذا المستخدم؟" class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-bold hover:bg-red-800" >إلغاء التفعيل</button>
                                    @endcan
                                </div>
                            </td>
                        </tr>

                    @endif
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="mt-4 text-gray-900 dark:text-white">
        {{ $users->links() }}
    </div>

    <!-- add \ edit user Modal  -->
    @if($showModal)
        <div class="fixed inset-0 z-[60] backdrop-blur-md overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        {{ $isEditing ? 'تعديل مستخدم' : 'إضافة مستخدم ' }}
                    </h3>

                    <form wire:submit.prevent="save">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الإسم</label>
                            <input type="text" wire:model="name"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">البريد الإلكتروني</label>
                            <input type="email" wire:model="email"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-300 mb-1">Company Name</label>

                            <input  type="text"  wire:model="company_name"
                                {{-- Lock the field if the logged-in user already has a company --}}
                                @if(auth()->user()->company_name)
                                    readonly
                                @endif

                                {{-- Change the background color based on whether it is locked --}}
                                class="w-full px-4 py-2 border rounded-md focus:outline-none
                                    {{ auth()->user()->company_name
                                        ? 'bg-gray-700 border-gray-600 text-gray-400 cursor-not-allowed'
                                        : 'bg-slate-800 border-gray-600 text-white focus:ring-2 focus:ring-blue-500'}}">
                            @error('company_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">كلمة المرور</label>
                            <input type="password" wire:model="password"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                    <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">الصلاحية</label>

    <select multiple wire:model="selectedRoles"
        @if($isSelf) disabled @endif
        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 text-gray-900 dark:text-white transition-colors
        {{ $isSelf ? 'bg-gray-100 dark:bg-gray-800 cursor-not-allowed opacity-60' : 'bg-white dark:bg-gray-700' }}">

        @foreach($roles as $role)
            <option value="{{ $role->name }}">{{ $role->name }}</option>
        @endforeach
    </select>

    @if($isSelf)
        <p class="text-xs text-orange-500 mt-2 font-bold flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            للحماية، لا يمكنك تعديل صلاحياتك الشخصية.
        </p>
    @endif
</div>

                        <div class="flex justify-end space-x-2">
                            <button type="button" wire:click="closeModal"
                                class="bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-4 py-2 rounded">
                                إلغاء
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




    @if($showInviteModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-xl w-96">
                <h2 class="text-lg font-bold mb-4">دعوة جديدة</h2>
                <input type="email" wire:model="inviteEmail" placeholder="ادخل البريد الإلكتروني "
                       class="w-full border p-2 mb-4 rounded">

                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('showInviteModal', false)" class="text-gray-500">إلغاء</button>
                    <button wire:click="sendInvite" class="bg-green-600 text-white px-4 py-2 rounded"> أرسل  </button>
                </div>
            </div>
        </div>
    @endif


    {{-- here to see the pop up for mobile view --}}

    @if($showDetailsModal && $selectedUserDetails)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-all" dir="rtl">
    <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col">

        <div class="bg-gray-800 p-5 flex justify-between items-center text-white">
            <h3 class="text-lg font-bold">تفاصيل المستخدم</h3>
            <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 space-y-6 text-right">
        <div>
            <div class="flex justify-between items-center mb-1">
                <h4 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                    {{ $selectedUserDetails->name }}

                    @if($selectedUserDetails->id === auth()->id())
                        <span class="mr-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-100 text-indigo-800">(أنت)</span>
                    @endif

                </h4>
                 <div class="flex gap-4">
                    @can('user.edit')
                        <button wire:click="edit({{ $selectedUserDetails->id }})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 font-bold text-sm">تعديل</button>
                    @endcan
                    @can('user.delete')
                        <button wire:click="delete({{ $selectedUserDetails->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-900 dark:text-red-400 font-bold text-sm">حذف</button>
                    @endcan
                </div>
                </div>
                <p class="text-sm text-indigo-600 font-mono mt-1">{{ $selectedUserDetails->email }}</p>
            </div>


            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-400 font-bold">المشرف:</span>

                @if($selectedUserDetails && $selectedUserDetails->manager)
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-gray-100 text-gray-800">
                        {{ $selectedUserDetails->manager->name }}
                    </span>
                @else
                    <span class="text-xs text-indigo-400 font-bold">Super Admin</span>
                @endif
            </div>



            <div class = "flex items-center gap-2">
                <span class=" text-xs text-gray-400 font-bold">الصلاحية:</span>
                    @forelse($selectedUserDetails->roles as $role)
                        <span class="inline-flex px-3 py-1 text-xs bg-blue-100 dark:bg-blue-700 text-blue-800 dark:text-white rounded-full font-bold">
                            {{ $role->name }}
                        </span>
                    @empty
                        <span class="text-sm text-gray-500">لا توجد صلاحية</span>
                    @endforelse
            </div>

            <hr class="border-gray-100 dark:border-gray-700">


            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border border-gray-100 dark:border-gray-600">
            @can('subscription.unactive.view')
                <span class="block text-sm font-bold text-gray-800 dark:text-white mb-3">إدارة الإشتراك:</span>
            @endcan
        <div class="flex gap-2">
            @can('subscription.view')
                <input type="date" wire:model="selectedDates.{{ $selectedUserDetails->id }}" class="w-full mb-3 border border-gray-300 rounded-lg px-3 py-2 text-sm text-black">


                    <button wire:click="grantAccess({{ $selectedUserDetails->id }})" class="flex-1 bg-green-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition">
                        تفعيل
                    </button>
            @endcan

            @can('subscription.unactive.view')
                    <button wire:click="cancelSubscription({{ $selectedUserDetails->id }})" wire:confirm="هل أنت متأكد من إغلاق النظام عن هذا المستخدم؟" class="flex-1 bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-red-800 transition">
                        إلغاء التفعيل
                    </button>
            @endcan
                </div>
            </div>


        </div>

        <div class="bg-gray-50 dark:bg-gray-900 p-4 flex justify-start border-t dark:border-gray-700">
            <button wire:click="closeDetailsModal" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white text-gray-800 px-6 py-2 rounded-xl font-bold transition-all">إلغاء</button>
        </div>

    </div>
</div>
@endif
</div>
