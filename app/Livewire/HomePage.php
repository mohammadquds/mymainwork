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




// here if you wanna see your search words in the url search bar also
    //     protected function queryString() {
    //     return [
    //         'search' => [
    //             'as' => 'q',
    //             'history' => true,
    //             'except' => ''
    //         ]
    //     ];
    // }



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
        $arabic = new \ArPHP\I18N\Arabic();

        // القوة هنا: نلغي $allowedIds ونستخدم ID المستخدم الحالي حصراً
        $data = \App\Models\form::where('user_id', auth()->id()) 
            ->when($this->startDate, fn($query) => $query->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate, fn($query) => $query->whereDate('created_at', '<=', $this->endDate))
            // إذا لم يحدد تاريخ، نكتفي بالشهر الحالي لضمان خفة الملف
            ->when(!$this->startDate && !$this->endDate, function($query) {
                $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // معالجة النصوص للـ PDF (دون لمس أصل البيانات لتجنب خطأ UTF-8)
        $pdfData = $data->map(function ($item) use ($arabic) {
            $item->pdf_name = $arabic->utf8Glyphs($item->full_name);
            $item->pdf_employee = $arabic->utf8Glyphs($item->employee_name ?? auth()->user()->name);
            $item->pdf_type = ($item->type == 'sale') ? $arabic->utf8Glyphs("بيع") : $arabic->utf8Glyphs("شراء");
            return $item;
        });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.report', [
            'pdf' => $pdfData,
            'arabic' => $arabic
        ]);

        $pdf->setPaper('A4', 'landscape');

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
        $salesQuery = form::where('user_id', auth()->id());

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
