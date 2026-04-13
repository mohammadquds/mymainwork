<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\form;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Maatwebsite\Excel\Facades\Excel;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesExport;


class HomePage extends Component
{

 use WithPagination;

    public $selectedSale = null; // Stores the sale object for the modal
    public $showModal = false;   // Toggles the modal visibility
    public $search ='';
    public $startDate;
    public $endDate;
    public $showExcelModal =false;
    // public $showCalculatorModal = false;



    // public function openCalculatorModal(){
    //     $this->showCalculatorModal = true;
    // }

    // public function closeCalculatorModal(){
    //     $this->showCalculatorModal = false;
    // }

    public function index(){
        $pdf = form::all();
        return view('reports.index', compact('pdf'));
   }
    public function viewPdf()
    {
        // جلب البيانات ومعالجتها للعربية
        $pdfData = $this->prepareArabicData();

        $pdf = Pdf::loadView('pdf.report', ['pdf' => $pdfData]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('report.pdf');
    }

    public function generatePdf()
    {
        // جلب البيانات ومعالجتها للعربية
        $pdfData = $this->prepareArabicData();

        $pdf = Pdf::loadView('pdf.report', ['pdf' => $pdfData]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download('gold_report.pdf');
    }

    // دالة مساعدة لمعالجة النصوص العربية في الجدول بالكامل
    private function prepareArabicData()
    {
        $arabic = new Arabic();
        $sales = form::all();

        // نقوم بالمرور على كل سجل وتعديل النصوص العربية فيه
        return $sales->map(function ($item) use ($arabic) {
            // نستخدم utf8Glyphs لربط الحروف ببعضها
            $item->customer_name = $arabic->utf8Glyphs($item->customer_name);
            $item->staff_name = $arabic->utf8Glyphs($item->staff_name);
            $item->shop_name = $arabic->utf8Glyphs($item->shop_name);

            // إذا كان نوع العملية (بيع/شراء) مكتوب بالعربي
            if($item->type == 'sale') $item->display_type = $arabic->utf8Glyphs("بيع");
            else $item->display_type = $arabic->utf8Glyphs("شراء");

            return $item;
        });
    }

    #[On('triggerExcelModal')]
    public function openExcelModal(){
        $this->showExcelModal =true;
    }

     public function closeExcelModal(){
        $this->showExcelModal =false;
    }

// Add this new function anywhere inside the class
public function exportExcel()
{
    // Generate a unique file name like "sales_2026_03_29.xlsx"
    $fileName = 'sales_' . now()->format('Y_m_d_His') . '.xlsx';

    return Excel::download(new SalesExport($this->startDate, $this->endDate), $fileName);
}


  // Add this at the top with your other 'use' statements

// Add this inside your class:
#[On('sale-added')]
public function refreshSales()
{
    // Livewire will automatically re-run the render() method and update the table!
}

    public function updatingSearch()
    {
        $this->resetPage();
    }

public function delete($id){
    form::find($id)->delete();

}


public function openDetails($id)
{
    // Get the specific sale clicked
    $this->selectedSale = form::findOrFail($id);
    $this->showModal = true;

    // Reset the modal's pagination back to page 1 when opening a new customer
    $this->resetPage('ordersPage');
}

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSale = null;
    }

    public function render()
    {

    // Query logic to handle searching across multiple columns
        $salesQuery = form::query();

        if (!empty($this->search)) {
            $salesQuery->where(function ($query) {
                $query->where('full_name', 'like', '%' . $this->search . '%')
                      ->orWhere('national_id', 'like', '%' . $this->search . '%')
                      ->orWhere('id_version_number', 'like', '%' . $this->search . '%');
            });
        }

 // This groups the rows so one ID = one line
    // We use MAX() on columns to satisfy the MySQL 'Strict' error
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

        // NEW: Fetch paginated orders ONLY for the modal
    $customerOrders = collect();
    if ($this->selectedSale) {
        $customerOrders = form::where('national_id', $this->selectedSale->national_id)
            ->orderBy('created_at', 'asc')
            ->paginate(2, ['*'], 'ordersPage'); // Show 3 at a time, name it 'ordersPage'
    }

    return view('livewire.home-page',compact('sales','customerOrders'))
    ->layout('layoutscreen.app');
    }
}



