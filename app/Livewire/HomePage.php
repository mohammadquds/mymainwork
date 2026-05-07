<?php

namespace App\Livewire;

use App\Exports\SalesExport;
use App\Models\form;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;



class HomePage extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $selectedSale = null;
    public $showModal = false;
    // #[Url(as:'q', except:'', history:true)]
    public $search = '';
    public $startDate;
    public $endDate;
    public $showExcelModal = false;
    public $showPdfModal = false;


    public $showOnboardingModal = false;
    public $vat_number;
    public $official_company_number;
    public $isEditMode = false;
   public $newPhoto;
    public $editingId;
    public $edit_weight, $edit_karat, $edit_price, $edit_full_name, $edit_national_id, $edit_id_version_number, $edit_employee_name, $edit_store_name, $edit_type, $edit_product_image, $edit_created_at, $edit_updated_at;


// here it will show the pop up of the vat number and others and after filling it it will disappear
public function mount(){
    $user=Auth::user();

    if ($user->hasRole('Admin')){
        if(empty($user->vat_number) || empty($user->official_company_number)) {
            $this->showOnboardingModal = true;

        }
    }
}
    public $editingSale = [
        'id' => null,
        'full_name' => '',
        'national_id' => '',
        'id_version_number' => '',
        'karat' => '',
        'weight' => '',
        'sale_price' => '',
        'unit_type' => '',
        'store_name' => '',
        'description' => '',
        'product_image' => null,
];

    public function editSale($id)
    {
        $this->authorize('user.edit');
        $sale = form::findOrFail($id);

        // جلب كل البيانات وتحويلها لمصفوفة لتعبئة الفورم تلقائياً
        $this->editingSale = $sale->toArray();
        $this->isEditMode = true;
    }

    public function updateSale()
    {
        // التحقق من كافة الحقول (بما فيها الهوية)
        $this->validate([
            'editingSale.full_name'   => 'required|string',
            'editingSale.national_id' => 'required|numeric',
            'editingSale.id_version_number' => 'nullable|numeric',
            'editingSale.karat'       => 'required',
            'editingSale.weight'      => 'required|numeric',
            'editingSale.sale_price'  => 'required|numeric',
            'editingSale.unit_type'   => 'required|string',
            'editingSale.description' => 'nullable|string',
        ]);

        $sale = form::findOrFail($this->editingSale['id']);

        if ($this->newPhoto) {
            $imagePath = $this->newPhoto->store('products', 'public');
            $this->editingSale['product_image'] = $imagePath;
        }

        // تحديث "كل شيء" في قاعدة البيانات
        $sale->update($this->editingSale);

        $this->isEditMode = false;
        $this->reset(['editingSale', 'newPhoto']);
        session()->flash('message', 'تم تحديث كافة البيانات بنجاح.');
    }
    public function duplicateSale($id)
    {
        $sale = form::findOrFail($id);
        $newSale = $sale->replicate();
        $newSale->created_at = now();
        $newSale->updated_at = now();
        $newSale->user_id = auth()->id();
        $newSale->save();

        session()->flash('message', 'تم تكرار السجل بنجاح.');
    }


    public function closeEditModal()
    {
        $this->isEditMode = false;
        $this->reset('editingSale');
    }


public function closeOnboardingModal()
    {
        $this->showOnboardingModal = false;
    }


public function saveCompanyDetails()
{
    $user = Auth::user();

    $companyUserIds = \App\Models\User::where('admin_id', $user->id)->pluck('id')->toArray();
    $companyUserIds[] = $user->id;

    $this->validate([
        'vat_number' => [
            'required',
            'max:20',
            Rule::unique('users')->whereNotIn('id', $companyUserIds)
        ],
        'official_company_number' => [
            'required',
            'max:20',
            Rule::unique('users')->whereNotIn('id', $companyUserIds)
        ],
    ]);

    $user->update([
        'vat_number' => $this->vat_number,
        'official_company_number' => $this->official_company_number,
    ]);

    \App\Models\User::where('admin_id', $user->id)->update([
        'vat_number' => $this->vat_number,
        'official_company_number' => $this->official_company_number,
    ]);

    $this->showOnboardingModal = false;

    session()->flash('message', 'تم اكتمال إعداد حسابك بنجاح! يمكنك الآن بدء المبيعات.');
}


    public function index()
    {
        $allowedIds = $this->getAllowedUserIds();
        $pdf = form::whereIn('user_id', $allowedIds)->get();
        return view('reports.index', compact('pdf'));
    }
     public function generatePdf()
    {
        return $this->handlePdfAction('download');
    }
    public function viewPdf()
    {
        return $this->handlePdfAction('stream');
    }

    /**
     * دالة موحدة للتعامل مع الـ PDF لضمان الخصوصية والاحترافية
     */
    private function handlePdfAction($action)
    {
        $arabic = new \ArPHP\I18N\Arabic();

        // 1. الخصوصية والفلترة الزمنية (صاحب الحساب + الفتره المحدده)
        $sales =    form::where('user_id', auth()->id());

        if ($this->startDate) {
            $sales->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $sales->whereDate('created_at', '<=', $this->endDate);
        }

         $sales = $sales->orderBy('created_at', 'asc')->get();


        // 2. معالجة النصوص العربية باحترافية
        $pdfData = $sales->map(function ($item) use ($arabic) {
            $item->full_name = $arabic->utf8Glyphs($item->full_name);
            $item->employee_name = $arabic->utf8Glyphs($item->employee_name);

            if ($item->type == 'sale')
                $item->display_type = $arabic->utf8Glyphs("بيع");
            else
                $item->display_type = $arabic->utf8Glyphs("شراء");

            return $item;
        });

        // 3. إنشاء الملف
        $pdf = Pdf::loadView('pdf.report', [
            'pdf' => $pdfData,
            'arabic' => $arabic
        ]);

        $pdf->setPaper('A4', 'landscape');
        $fileName = 'gold_report_' . now()->format('m_Y') . '.pdf';

        return ($action == 'download') ? $pdf->download($fileName) : $pdf->stream($fileName);
    }


   public function viewSinglePdf($id)
    {
         $allowedIds = $this->getAllowedUserIds();

        $sale = form::whereIn('user_id', $allowedIds)->findOrFail($id);
        $arabic = new Arabic();

        $sale->full_name = $arabic->utf8Glyphs($sale->full_name);
        $sale->employee_name = $arabic->utf8Glyphs($sale->employee_name);
        $sale->store_name = $arabic->utf8Glyphs($sale->store_name);
        $sale->product_image = ($sale->product_image);

        if ($sale->product_image) {
            $sale->product_image_path = public_path('storage/' . $sale->product_image);
        } else {
            $sale->product_image_path = null;
        }

        $pdf = Pdf::loadView('pdf.report', [
            'pdf' => collect([$sale]),
            'arabic' => $arabic
        ])
            ->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        $pdf = Pdf::loadView('pdf.single_invoice', [
            'sale' => $sale,
            'arabic' => $arabic
        ]);

        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('invoice_' . $sale->id . '.pdf');
    }



    #[On('triggerExcelModal')]
    public function openExcelModal()
    {
        $this->showExcelModal = true;
    }

    public function closeExcelModal()
    {
        $this->showExcelModal = false;
    }

    public function exportExcel()
    {
        $fileName = 'sales_' . now()->format('Y_m_d_His') . '.xlsx';

         $allowedIds = $this->getAllowedUserIds();
        return Excel::download(new SalesExport($this->startDate, $this->endDate, $allowedIds), $fileName);
    }

#[On('triggerPdfModal')]
    public function openPdfModal()
    {
        $this->showPdfModal = true;
    }

    public function closePdfModal()
    {
        $this->showPdfModal = false;
    }

    public function exportPdf()
    {
        $fileName = 'sales_' . now()->format('Y_m_d_His') . '.pdf';
        $arabic = new Arabic();
        $allowedIds = $this->getAllowedUserIds();

        $data = form::whereIn('user_id', $allowedIds)
            ->when($this->startDate, fn($query) => $query->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($query) => $query->whereDate('created_at', '<=', $this->endDate))
            ->orderBy('created_at', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.report', [
            'pdf' => $data,
            'arabic' => $arabic
        ]);

        $pdf->setPaper('A4', 'landscape');

        // استخدام الـ Response لفرض نوع الملف PDF
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    #[On('sale-added')]
    public function refreshSales()
    {
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        form::find($id)->delete();
    }
    public function edit($id)
    {
        $this->authorize('user.edit');

        $sale = form::findOrFail($id);
        $this->selectedSale = $sale;
        $this->showModal = true;
    }


    public function openDetails($id)
    {
        $allowedIds = $this->getAllowedUserIds();

        // SECURITY FIX: Only find the form IF it belongs to an allowed user
        $this->selectedSale = form::whereIn('user_id', $allowedIds)->findOrFail($id);

        $this->showModal = true;
        $this->resetPage('ordersPage');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSale = null;
    }


     private function getAllowedUserIds()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            return \App\Models\User::pluck('id')->toArray();
        }
        $topBossId = $user->admin_id ? $user->admin_id : $user->id;
        $topBoss = \App\Models\User::find($topBossId);

        if (!$topBoss) {
            $topBoss = $user;
        }

        $allowedIds = [$topBoss->id];
        if ($topBoss->children && $topBoss->children->count() > 0) {
            $childIds = $topBoss->children->pluck('id')->toArray();
            $allowedIds = array_merge($allowedIds, $childIds);
        }
        return $allowedIds;
    }


    public function render()
    {
        // 1. جلب المعرفات المسموح بها للمستخدم الحالي
        $allowedIds = $this->getAllowedUserIds();

        // حساب إجمالي المبيعات
        $totalSales = \App\Models\form::whereIn('user_id', $allowedIds)->count();

        // 2. جلب قائمة العملاء الفريدين (مرة واحدة وبكل البيانات المطلوبة)
        $clients = \App\Models\form::whereIn('user_id', $allowedIds)
            ->select(
                'national_id',
                \DB::raw('MAX(full_name) as full_name'),
                \DB::raw('MAX(date_of_birth) as date_of_birth'),
                \DB::raw('MAX(created_at) as last_transaction')
            )
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                    ->orWhere('national_id', 'like', '%' . $this->search . '%');
                });
            })
            ->groupBy('national_id')
            ->orderBy('last_transaction', 'desc')
            ->get();

        // 3. جلب العمليات لكل عميل وإضافتها له (هذا الجزء يمنع خطأ null)
        foreach ($clients as $client) {
            $client->orders = \App\Models\form::where('national_id', $client->national_id)
                ->whereIn('user_id', $allowedIds)
                ->orderBy('created_at', 'desc')
                ->get(); // نضمن هنا أن orders لن تكون null أبداً
        }

        return view('livewire.home-page', [
            'clients' => $clients,
            'totalSales' => $totalSales,
        ])->layout('layoutscreen.app');
    }

}

