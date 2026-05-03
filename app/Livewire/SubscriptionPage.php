<?php

namespace App\Livewire;
use App\Models\Subscriptions;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class SubscriptionPage extends Component
{

    use WithPagination;
    public $showModal = false;
    public $selectedSub = null;
    public $search = '';
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openDetails($id)
    {
        $this->selectedSub = User::findOrFail($id);
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSub = null;
    }


    public function render()
{
    // 1. بدأ الاستعلام من موديل المستخدم (User) لجلب أصحاب الاشتراكات
    // نستخدم eager loading للعلاقات إذا كانت موجودة لتقليل الضغط على القاعدة
    $query = \App\Models\User::query();

    // 2. تطبيق منطق البحث (المحاكي للكود الناجح)
    // إذا قام المستخدم بالكتابة في السيرش بار، يتم البحث في الاسم، الشركة، والبريد
    if (!empty($this->search)) {
        $query->where(function($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('email', 'like', '%' . $this->search . '%')
              ->orWhere('company_name', 'like', '%' . $this->search . '%');
        });
    } else {
        // 3. إذا لم يكن هناك بحث، نعرض فقط المدراء (الذين ليس لديهم admin_id)
        // هذا يحافظ على شكل الصفحة مرتباً كما في الكود الذي أرسلته
        $query->whereNull('admin_id');
    }

    // 4. التنفيذ النهائي مع الترتيب والتقسيم (Pagination)
    // نجعل النتائج الأحدث تظهر أولاً
    $subscriptions = $query->orderBy('id', 'desc')
                           ->paginate(10);

    
        return view('livewire.subscription-page', ['subscriptions' => $subscriptions])
            ->layout('layoutscreen.app');
    }
}
