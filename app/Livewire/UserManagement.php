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
    public ?\App\Models\User $selectedUserDetails = null;
    public $company_name;
    public $isCompanyLocked = false;



    // Add these methods to handle opening and closing
    public function openDetails($id)
    {
        $this->selectedUserDetails = \App\Models\User::findOrFail($id);
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
        // $this->showDetailsModal = false;
        // Check if the user is editing themselves
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


        // --- SECURITY CHECK: Dynamic Hierarchy ---
        $isSuperAdmin = Auth::user()->hasRole('Super Admin');
        $myPowerLevel = Auth::user()->roles->max(fn($role) => $role->permissions->count()) ?? 0;

        // Get a list of role names I am actually allowed to assign
        $allowedRoleNames = Role::with('permissions')->get()
            ->filter(function ($role) use ($myPowerLevel, $isSuperAdmin) {
                if ($isSuperAdmin)
                    return true; // Super Admin bypasses the limit
                return $role->permissions->count() < $myPowerLevel; // STRICTLY less than (<)
            })
            ->pluck('name')
            ->toArray();

        // Strip out any roles from the form that are above my power level
        $this->selectedRoles = array_intersect($this->selectedRoles, $allowedRoleNames);


        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'company_name' => $this->company_name,
        ];

        if (!$this->isEditing || $this->password) {
            $userData['password'] = Hash::make($this->password);
        }

        if ($this->isEditing) {
            $user = User::findOrFail($this->userId);
            $user->update($userData);

            // ONLY sync roles if they are NOT editing themselves
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


    // send emails invitation?
    public $showInviteModal = false;
    public $inviteEmail = '';

    public function sendInvite()
    {
        $this->validate(['inviteEmail' => 'required|email']);

        $admin = auth()->user();

        // 1. If this Admin doesn't have a code yet, generate one instantly!
        if (!$admin->invite_code) {
            $admin->update(['invite_code' => \Illuminate\Support\Str::random(8)]);
        }

        // 2. Build the beautiful, clean URL (e.g., mainwork.test/register-account?ref=Xy7P2mQ1)
        $url = url('/register-account?ref=' . $admin->invite_code);

        // 3. Send the email
        \Illuminate\Support\Facades\Mail::to($this->inviteEmail)->send(new \App\Mail\TestMail($url, $admin->name));

        $this->showInviteModal = false;
        $this->inviteEmail = '';
        session()->flash('status', 'Invitation sent successfully!');
    }


    // Change this line
    public $selectedDates = []; // Use an array
    public function grantAccess($userId)
    {
        // 1. Get the date from the array we created
        $date = $this->selectedDates[$userId] ?? null;

        if (!$date) {
            session()->flash('error', "يرجى اختيار تاريخ");
            return;
        }

        // 2. Update the main user (Admin)
        $user = User::findOrFail($userId);
        $user->update([
            'start_date' => now(),
            'end_date' => $date,
            'status' => 'active'
        ]);

        // 3. --- NEW: UPDATE ALL CHILDREN INSTANTLY ---
        // Find everyone who belongs to this Admin and give them the exact same new dates!
        User::where('admin_id', $user->id)->update([
            'start_date' => now(),
            'end_date' => $date,
            'status' => 'active'
        ]);
        // ---------------------------------------------

        // Clear the input field for that user after success
        unset($this->selectedDates[$userId]);

        session()->flash('message', "تم تفعيل الاشتراك لـ {$user->name} وجميع التابعين له بنجاح.");
    }


    public function cancelSubscription($userId)
    {
        $user = User::findOrFail($userId);

        // 1. Cancel the main user (The Admin)
        $user->update([
            'end_date' => now()->subDay(),
            'status' => 'expired'
        ]);

        // 2. --- NEW: CANCEL ALL CHILDREN ---
        // Find anyone who has this Admin's ID and cancel them too!
        User::where('admin_id', $user->id)->update([
            'end_date' => now()->subDay(),
            'status' => 'expired'
        ]);
        // -----------------------------------

        $this->dispatch('subscription-updated');

        session()->flash('message', "تم إغلاق اشتراك {$user->name} وجميع التابعين له بنجاح.");
    }

    public function render()
    {
        $this->authorize('user.view');

        // 1. Calculate MY "Power Level" (the highest number of permissions I have)
        $myPowerLevel = Auth::user()->roles->max(function ($role) {
            return $role->permissions->count();
        }) ?? 0;

        // 2. Find roles that are "too high" for me to manage
        $forbiddenRoleNames = Role::with('permissions')->get()->filter(function ($role) use ($myPowerLevel) {
            return $role->permissions->count() > $myPowerLevel;
        })->pluck('name');


        // 1. Fetch Users, eagerly loading roles and relationships
        $query = User::with(['roles', 'children.roles', 'manager']);

        // 2. Filter the view based on the logged-in user
        if (Auth::user()->hasRole('Super Admin')) {

            $query->whereDoesntHave('manager', function ($managerQuery) {
                $managerQuery->whereHas('roles', function ($roleQuery) {
                    $roleQuery->where('name', 'Admin');
                });
            });
        } else {

            $query->where('id', Auth::id());
        }

        // 3. Keep your forbidden roles filter
        if ($forbiddenRoleNames->isNotEmpty()) {
            $query->whereDoesntHave('roles', function ($q) use ($forbiddenRoleNames) {
                $q->whereIn('name', $forbiddenRoleNames);
            });
        }

        $users = $query->orderByRaw('id = ? DESC', [Auth::id()])
            ->orderBy('created_at', 'asc')
            ->paginate(10);



        // 4. Filter the Roles dropdown: Only show roles I am allowed to assign
        $isSuperAdmin = Auth::user()->hasRole('Super Admin');

        $roles = Role::with('permissions')->get()->filter(function ($role) use ($myPowerLevel, $isSuperAdmin) {
            // Super Admins can see and assign all roles
            if ($isSuperAdmin) {
                return true;
            }

            // Normal Admins can ONLY see roles strictly LESS THAN (<) their own power level
            return $role->permissions->count() < $myPowerLevel;
        });

        return view('livewire.user-management', compact(['users', 'roles']))
            ->layout('layoutscreen.app');
    }
}
