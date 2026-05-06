<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class UserManagement extends Component
{

    use WithPagination;

    public $userId;
    public $name;
    public $email;
    public $password;
    public $selectedRoles = [];
    public $isEditing = false;
    public $showModal = false;
    public $isSelf = false;
    public $showDetailsModal = false;
    public ?User $selectedUserDetails = null;
    public $company_name;
    public $vat_number;
    public $official_company_number;
    public $isCompanyLocked = false;
    public $search = '';
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openDetails($id)
    {
        $this->selectedUserDetails = User::findOrFail($id);
        $this->showDetailsModal = true;
    }
    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedUserDetails = null;
    }


    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'company_name' => 'required | max:255',
        'password' => 'required|min:8',
        'selectedRoles' => 'array',
    ];

    public function create()
    {

        $this->authorize('user.create');
        $this->resetForm();
        $this->company_name = auth()->user()->company_name;
        $this->showModal = true;
    }


    public function edit($id)
    {
        $this->authorize('user.edit');
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->company_name = $user->company_name;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->isEditing = true;
        $this->showModal = true;
        // will check if the user editing himself
        $this->isSelf = ($user->id === Auth::id());
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->authorize('user.edit');
            $this->rules['email'] = 'required|email|unique:users,email,' . $this->userId;
            $this->rules['password'] = 'nullable|min:8';
            $this->rules['company_name'] = 'nullable';
        } else {
            $this->authorize('user.create');
        }
        $this->validate();

        // super admin can give permissions to any one
        $isSuperAdmin = Auth::user()->hasRole('Super Admin');
        $myPowerLevel = Auth::user()->roles->max(fn($role) => $role->permissions->count()) ?? 0;

        $allowedRoleNames = Role::with('permissions')->get()
            ->filter(function ($role) use ($myPowerLevel, $isSuperAdmin) {
                if ($isSuperAdmin)
                    return true;
                return $role->permissions->count() < $myPowerLevel;
            })
            ->pluck('name')
            ->toArray();
        // you can give permission to new user but the role have to be lower than you
        $this->selectedRoles = array_intersect($this->selectedRoles, $allowedRoleNames);


        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'company_name' => auth()->user()->company_name,
            'vat_number' => auth()->user()->vat_number,
            'official_company_number' => auth()->user()->official_company_number,
        ];

        if (!$this->isEditing || $this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            $user = User::findOrFail($this->userId);
            $user->update($userData);

            if (!$this->isSelf) {
                $user->syncRoles($this->selectedRoles);
            }
        } else {
            $userData['admin_id'] = Auth::id();

            $userData['start_date'] = Auth::user()->start_date;
            $userData['end_date'] = Auth::user()->end_date;
            $userData['status'] = Auth::user()->status ?? 'active';

            $user = User::create($userData);
            $user->syncRoles($this->selectedRoles);
        }

        $this->resetForm();
        $this->showModal = false;
        session()->flash('message', 'User saved successfully!');
    }


    // this will delete but you cant delete your self
    public function delete($id)
    {
        $this->authorize('user.delete');

        $userToDelete = User::findOrFail($id);

        if ($userToDelete->id === auth()->id()) {
            session()->flash('message', 'Error: You cannot delete your own account!');
            return;
        }
        if ($userToDelete->hasRole('Super Admin')) {
            session()->flash('message', 'Error: Super Admin accounts cannot be deleted!');
            return;
        }

        $userToDelete->delete();
        if ($this->selectedUserDetails && $this->selectedUserDetails->id === $id) {
            $this->closeDetailsModal();
        }
        session()->flash('message', 'User deleted successfully!');
    }




    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }



    public function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->company_name = '';
        $this->password = '';
        $this->selectedRoles = ['user'];
        $this->isEditing = false;
        $this->isSelf = false;
        $this->resetValidation();
    }


    // send emails invitation
    public $showInviteModal = false;
    public $inviteEmail = '';

    public function sendInvite()
    {
        $this->validate(['inviteEmail' => 'required|email']);

        $admin = auth()->user();

        //  If Admin doesn't have a code yet generate new one
        if (!$admin->invite_code) {
            $admin->update(['invite_code' => \Illuminate\Support\Str::random(8)]);
        }

        $url = url('/register-account?ref=' . $admin->invite_code);

        Mail::to($this->inviteEmail)->send(new TestMail($url, $admin->name));

        $this->showInviteModal = false;
        $this->inviteEmail = '';
        session()->flash('status', 'Invitation sent successfully!');
    }


    public $selectedDates = [];
    public function grantAccess($userId)
    {
        $date = $this->selectedDates[$userId] ?? null;

        if (!$date) {
            session()->flash('error', "يرجى اختيار تاريخ");
            return;
        }

        $user = User::findOrFail($userId);
        $user->update([
            'start_date' => now(),
            'end_date' => $date,
            'status' => 'active'
        ]);

        //    here if he has child will update them also
        User::where('admin_id', $user->id)->update([
            'start_date' => now(),
            'end_date' => $date,
            'status' => 'active'
        ]);
        unset($this->selectedDates[$userId]);

        session()->flash('message', "تم تفعيل الاشتراك لـ {$user->name} وجميع التابعين له بنجاح.");
    }


    public function cancelSubscription($userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'end_date' => now()->subDay(),
            'status' => 'expired'
        ]);
        //    here if he has child will cancel them also
        User::where('admin_id', $user->id)->update([
            'end_date' => now()->subDay(),
            'status' => 'expired'
        ]);
        $this->dispatch('subscription-updated');

        session()->flash('message', "تم إغلاق اشتراك {$user->name} وجميع التابعين له بنجاح.");
    }


    // if you have more permissions you can change everything but if you dont you can change the lower
    public function render()
    {
        $this->authorize('user.view');

        // 1. حساب مستوي القوة (Power Level) والصلاحيات المحظورة
        $myPowerLevel = Auth::user()->roles->max(function ($role) {
            return $role->permissions->count();
        }) ?? 0;

        $forbiddenRoleNames = Role::with('permissions')->get()->filter(function ($role) use ($myPowerLevel) {
            return $role->permissions->count() > $myPowerLevel;
        })->pluck('name');

        // 2. بدأ الاستعلام الأساسي مع العلاقات
        $query = User::with(['roles', 'children.roles', 'manager']);

        // 3. تطبيق منطق البحث (مدمج)
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('company_name', 'like', '%' . $this->search . '%');
            });
        }

        // 4. تطبيق منطق الحماية والأدوار (الـ Authorization القديم الخاص بك)
        if (Auth::user()->hasRole('Super Admin')) {
            // السوبر أدمن لا يرى من مديرهم "Admin" (حسب منطقك)
            $query->whereDoesntHave('manager', function ($managerQuery) {
                $managerQuery->whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'Admin');
                });
            });

            // إذا لم يكن هناك بحث، نعرض المدراء فقط (اختياري حسب رغبتك)
            if (empty($this->search)) {
                $query->whereNull('admin_id');
            }
        } else {
            // المستخدم العادي يرى نفسه فقط
            $query->where('id', Auth::id());
        }

        // منع رؤية الأدوار المحظورة
        if ($forbiddenRoleNames->isNotEmpty()) {
            $query->whereDoesntHave('roles', function ($q) use ($forbiddenRoleNames) {
                $q->whereIn('name', $forbiddenRoleNames);
            });
        }

        // 5. التنفيذ النهائي (Pagination) والترتيب
        $users = $query->orderByRaw('id = ? DESC', [Auth::id()])
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        // 6. تجهيز الأدوار المتاحة للمودال
        $isSuperAdmin = Auth::user()->hasRole('Super Admin');
        $roles = Role::with('permissions')->get()->filter(function ($role) use ($myPowerLevel, $isSuperAdmin) {
            if ($isSuperAdmin) return true;
            return $role->permissions->count() < $myPowerLevel;
        });

        return view('livewire.user-management', compact('users', 'roles'))
            ->layout('layoutscreen.app');
    }
}
