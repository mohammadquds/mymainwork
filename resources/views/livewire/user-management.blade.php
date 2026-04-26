<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" dir="rtl">

    {{-- HEADER --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                <div class="p-2 bg-amber-500/10 rounded-lg">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                إدارة المستخدمين
            </h1>
            <p class="text-sm text-slate-500 font-medium mt-1">إجمالي الحسابات المسجلة: <span class="text-amber-600 font-bold">{{ $users->total() }}</span></p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            @can('user.create')
                <button wire:click="create" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-black transition-all shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    إضافة مستخدم
                </button>
            @endcan
            <button wire:click="$set('showInviteModal', true)" class="bg-amber-500 text-slate-900 px-6 py-2.5 rounded-xl font-bold hover:bg-amber-400 transition-all shadow-lg shadow-amber-100 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                دعوة بريد
            </button>
        </div>
    </div>

    {{-- Floating Notification --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 4000)"
             class="fixed top-5 left-1/2 transform -translate-x-1/2 z-[9999] w-[90%] max-w-md bg-green-500 text-white px-6 py-3 rounded-2xl shadow-2xl font-bold flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('message') }}</span>
            </div>
            <button @click="show = false" class="text-white/60 hover:text-white">&times;</button>
        </div>
    @endif

    {{-- USERS LIST --}}
    <div class="space-y-3">
        @foreach($users as $user)
            <div x-data="{ expanded: false }" class="group bg-white border border-slate-200 rounded-2xl overflow-hidden hover:border-amber-400 hover:shadow-md transition-all duration-200">

                {{-- Main Row --}}
                <div @click="expanded = !expanded" class="p-4 flex flex-col md:flex-row items-center justify-between gap-4 cursor-pointer">

                    {{-- # ID & Name --}}
                    <div class="flex items-center gap-4 w-full md:w-1/3">
                        <div class="bg-slate-50 border border-slate-100 text-slate-500 w-12 h-12 rounded-xl flex items-center justify-center font-black shadow-sm group-hover:bg-amber-100 group-hover:text-amber-700 transition-colors text-xs text-center leading-none">
                            #{{ ($users->currentPage()-1) * $users->perPage() + $loop->iteration }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 group-hover:text-amber-600 transition-colors flex items-center gap-2">
                                {{ $user->name }}
                                @if($user->id === auth()->id())
                                    <span class="bg-indigo-100 text-indigo-700 text-[10px] px-2 py-0.5 rounded-lg font-bold">(أنت)</span>
                                @endif
                            </h3>
                            <p class="text-xs text-slate-400 font-mono">{{ $user->email }}</p>
                        </div>
                    </div>

                    {{-- Roles & Manager --}}
                    <div class="flex items-center justify-around w-full md:w-1/3 md:border-r md:border-l border-slate-100 px-4 py-2 md:py-0 bg-slate-50 md:bg-transparent rounded-xl md:rounded-none">
                        <div class="text-center">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">الصلاحية</p>
                            @foreach($user->roles as $role)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">المشرف</p>
                            <p class="text-sm font-bold text-slate-800">{{ $user->manager->name ?? 'System' }}</p>
                        </div>
                    </div>
{{-- Subscription & Roles --}}
                    <div class="flex items-center justify-around w-full md:w-2/4 md:border-r md:border-l border-slate-100 px-4 py-2 md:py-0 bg-slate-50 md:bg-transparent rounded-xl md:rounded-none">
                        {{-- تفعيل وإلغاء الاشتراك --}}
                        <div class="flex items-center gap-2" @click.stop="">
                            @if($user->id !== auth()->id())
                                @can('subscription.view')
                                    <div class="flex items-center gap-1 bg-white border border-slate-200 p-1 rounded-xl shadow-sm">
                                        <input type="date" wire:model="selectedDates.{{ $user->id }}" class="border-none bg-transparent text-[10px] p-1 focus:ring-0 text-slate-600 font-bold">
                                        <button wire:click.stop="grantAccess({{ $user->id }})" class="bg-green-600 text-white px-3 py-1 rounded-lg text-[10px] font-black hover:bg-green-700 transition-colors">تفعيل</button>
                                    </div>
                                @endcan

                                @can('subscription.unactive.view')
                                    <button wire:click.stop="cancelSubscription({{ $user->id }})"
                                            wire:confirm="تنبيه: سيتم إغلاق اشتراك هذا الحساب، هل أنت متأكد؟"
                                            class="bg-red-50 text-red-600 px-3 py-2 rounded-xl text-[10px] font-black hover:bg-red-600 hover:text-white transition-all">إلغاء التفعيل</button>
                                @endcan
                            @else
                                <span class="text-[10px] text-slate-400 font-bold bg-slate-100 px-3 py-1 rounded-full italic">اشتراكك الشخصي نشط</span>
                            @endif
                        </div>
                    </div>


                    {{-- Actions & Toggle --}}
                    <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-1/3">
                        <div class="flex gap-2">
                            @can('user.edit')
                                <button wire:click.stop="edit({{ $user->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                            @endcan

                            @if($user->id !== auth()->id())
                                @can('user.delete')
                                    <button
                                        wire:click.stop="delete({{ $user->id }})"
                                        wire:confirm="هل أنت متأكد تماماً من حذف المستخدم ({{ $user->name }})؟ لا يمكن التراجع عن هذه العملية."
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endcan
                            @endif
                        </div>

                        @if($user->children && $user->children->count() > 0)
                            <div class="text-slate-300 transition-transform duration-300" :class="expanded ? 'rotate-180 text-amber-500' : ''">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        @else
                            <div class="w-6"></div>
                        @endif
                    </div>
                </div>

      {{-- Children Section (Employees) --}}
                @if($user->children && $user->children->count() > 0)
                    <div x-show="expanded" x-collapse x-cloak class="bg-slate-50 border-t border-slate-100 p-4 space-y-3">
                        @foreach($user->children as $child)
                            <div class="flex flex-col md:flex-row items-center justify-between bg-white p-4 rounded-xl border border-slate-200 mr-2 md:mr-8 shadow-sm gap-4 hover:border-amber-300 transition-colors">

                                {{-- 1. Name & Email --}}
                                <div class="flex items-center gap-3 w-full md:w-1/3">
                                    <div class="w-1.5 h-10 bg-amber-400 rounded-full shrink-0"></div>
                                    <div class="flex flex-col min-w-0">
                                        <p class="text-base font-black text-slate-800 truncate">{{ $child->name }}</p>
                                        <p class="text-xs text-slate-500 font-mono truncate">{{ $child->email }}</p>
                                    </div>
                                </div>

                                
                                {{-- 2. Roles & Manager --}}
                                <div class="flex items-center justify-around w-full md:w-1/3 md:border-r md:border-l border-slate-100 px-4 py-2 md:py-0 bg-slate-50 md:bg-transparent rounded-xl md:rounded-none">
                                    <div class="text-center">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">الصلاحية</p>
                                        @forelse($child->roles as $role)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-[10px] text-slate-400">لا يوجد</span>
                                        @endforelse
                                    </div>
                                    <div class="text-center">
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">المشرف</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $child->manager->name ?? $user->name }}</p>
                                    </div>
                                </div>

                                {{-- 3. Subscription --}}
                                <div class="flex items-center justify-around w-full md:w-2/4 md:border-r md:border-l border-slate-100 px-4 py-2 md:py-0 bg-slate-50 md:bg-transparent rounded-xl md:rounded-none">
                                    <div class="flex items-center gap-2" @click.stop="">
                                        @if($child->id !== auth()->id())
                                            @can('subscription.view')
                                                <div class="flex items-center gap-1 bg-white border border-slate-200 p-1 rounded-xl shadow-sm">
                                                    <input type="date" wire:model="selectedDates.{{ $child->id }}" class="border-none bg-transparent text-[10px] p-1 focus:ring-0 text-slate-600 font-bold">
                                                    <button wire:click.stop="grantAccess({{ $child->id }})" class="bg-green-600 text-white px-3 py-1 rounded-lg text-[10px] font-black hover:bg-green-700 transition-colors">تفعيل</button>
                                                </div>
                                            @endcan
                                            @can('subscription.unactive.view')
                                                <button wire:click.stop="cancelSubscription({{ $child->id }})"
                                                        wire:confirm="تنبيه: سيتم إغلاق اشتراك هذا الحساب، هل أنت متأكد؟"
                                                        class="bg-red-50 text-red-600 px-3 py-2 rounded-xl text-[10px] font-black hover:bg-red-600 hover:text-white transition-all">إلغاء التفعيل</button>
                                            @endcan
                                        @else
                                            <span class="text-[10px] text-slate-400 font-bold bg-slate-100 px-3 py-1 rounded-full italic">اشتراكك الشخصي نشط</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- 4. Actions (Edit / Delete) --}}
                                <div class="flex items-center justify-between md:justify-end gap-6 w-full md:w-1/3">
                                    <div class="flex gap-2">
                                        @can('user.edit')
                                            <button wire:click.stop="edit({{ $child->id }})" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </button>
                                        @endcan
                                        @can('user.delete')
                                            <button wire:click.stop="delete({{ $child->id }})" wire:confirm="حذف الموظف ({{ $child->name }})؟" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        @endcan
                                    </div>
                                    {{-- Invisible spacer to match the parent's dropdown arrow width --}}
                                    <div class="w-6"></div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-8" dir="ltr">
        {{ $users->links() }}
    </div>

    {{-- INVITE MODAL --}}
    @if($showInviteModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-slate-900 p-5 flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold text-amber-400">إرسال دعوة</h3>
                    <button wire:click="$set('showInviteModal', false)" class="text-slate-400 hover:text-white">&times;</button>
                </div>
                <div class="p-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2 text-right">البريد الإلكتروني للمدعو</label>
                    <input type="email" wire:model="inviteEmail" placeholder="example@email.com"
                           class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-right bg-slate-50 focus:ring-amber-500 focus:border-amber-500 transition-all">
                </div>
                <div class="bg-slate-50 p-6 flex justify-end gap-3 border-t border-slate-100">
                    <button wire:click="$set('showInviteModal', false)" class="text-slate-500 font-bold px-4">إلغاء</button>
                    <button wire:click="sendInvite" class="bg-green-600 text-white px-8 py-2 rounded-xl font-bold">إرسال الدعوة</button>
                </div>
            </div>
        </div>
    @endif

    {{-- CREATE / EDIT USER MODAL --}}
    @if($showModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden text-right">
                <div class="bg-slate-900 p-5 flex justify-between items-center text-white">
                    <h3 class="text-lg font-bold text-amber-400">{{ $isEditing ? 'تعديل بيانات المستخدم' : 'إضافة مستخدم جديد' }}</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-white">&times;</button>
                </div>
                <form wire:submit.prevent="save" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">الاسم</label>
                            <input type="text" wire:model="name" class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm bg-slate-50">
                            @error('name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-1">البريد الإلكتروني</label>
                            <input type="email" wire:model="email" class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm bg-slate-50 font-mono">
                            @error('email') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">اسم الشركة</label>
                        <input type="text" wire:model="company_name" @if(auth()->user()->company_name) readonly @endif
                               class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm {{ auth()->user()->company_name ? 'bg-slate-200 cursor-not-allowed text-slate-500' : 'bg-slate-50' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">كلمة المرور (اتركه فارغاً إذا لا تريد التغيير)</label>
                        <input type="password" wire:model="password" class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm bg-slate-50">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">الصلاحيات</label>
                        <select multiple wire:model="selectedRoles" @if($isSelf) disabled @endif
                                class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm {{ $isSelf ? 'bg-slate-200 cursor-not-allowed' : 'bg-slate-50' }}">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @if($isSelf) <p class="text-[9px] text-amber-600 mt-1">لا يمكنك تعديل صلاحياتك بنفسك.</p> @endif
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="text-slate-500 font-bold px-4">إلغاء</button>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-2 rounded-xl font-bold shadow-lg shadow-indigo-100 transition-all hover:bg-indigo-700">حفظ البيانات</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
