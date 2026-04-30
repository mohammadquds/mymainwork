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
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;



class HomePage extends Component
{
    use WithPagination;

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
    'karat' => '',
    'weight' => '',
    'sale_price' => '',
    'created_at' => '',
    'updated_at' => '',
    'product_image' => '',
];

public function editSale($id)
{
    // جلب بيانات المبيع المختار
    $sale = form::findOrFail($id); // تأكد من اسم الموديل لديك (Sale أو Form)
    
    $this->editingSale = [
        'id' => $sale->id,
        'full_name' => $sale->full_name,
        'karat' => $sale->karat,
        'weight' => $sale->weight,
        'sale_price' => $sale->sale_price,
        'created_at' => $sale->created_at,
        'updated_at' => $sale->updated_at,
        'product_image' => $sale->product_image,
    ];
    
    $this->isEditMode = true;
}

public function closeEditModal()
{
    $this->isEditMode = false;
    $this->reset('editingSale');
}

public function updateSale()
{
    $sale = form::findOrFail($this->editingSale['id']);
    
    $sale->update([
        'full_name' => $this->editingSale['full_name'],
        'karat' => $this->editingSale['karat'],
        'weight' => $this->editingSale['weight'],
        'sale_price' => $this->editingSale['sale_price'],
        'product_image' => $this->editingSale['product_image'],
    ]);

    $this->closeEditModal();
    $this->dispatch('swal', title: 'تم التحديث بنجاح'); // اختيارية إذا كنت تستخدم SweetAlert
}

public function closeOnboardingModal()
    {
        $this->showOnboardingModal = false;
    }

public function saveCompanyDetails(){
    $this->validate([
        'vat_number' => 'required|max:20',
        'official_company_number' => 'required|max:20',
    ]);
$user= Auth::user();

$user->update([
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
// Get the list of IDs this person is allowed to see
        $allowedIds = $this->getAllowedUserIds();
        $salesQuery = form::whereIn('user_id', $allowedIds);

        if (!empty($this->search)) {
            $salesQuery->where(function ($query) {
                $query->where('full_name', 'like', '%' . $this->search . '%')
                    ->orWhere('national_id', 'like', '%' . $this->search . '%')
                    ->orWhere('id_version_number', 'like', '%' . $this->search . '%');
            });
        }



        $sales = $groupedSales = $salesQuery->selectRaw('
            MAX(id) as id,
            national_id,
            MAX(full_name) as full_name,
            MAX(created_at) as created_at,
            MAX(karat) as karat,
            MAX(weight) as weight,
            MAX(sale_price) as sale_price
        ')
            ->groupBy('national_id')
            ->orderBy('created_at', 'asc')
            ->paginate(10);



        $customerOrders = collect();
        if ($this->selectedSale) {

            $allowedIds = $this->getAllowedUserIds();
            $customerOrders = form::where('national_id', $this->selectedSale->national_id)
                ->whereIn('user_id', $allowedIds)
                ->orderBy('created_at', 'asc')
                ->paginate(2, ['*'], 'ordersPage');
        }

        return view('livewire.home-page', compact('sales', 'customerOrders'))
            ->layout('layoutscreen.app');
    }
}
