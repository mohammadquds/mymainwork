<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\form;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class SalesForm extends Component
{

use WithFileUploads;

    // Model Properties
    public $full_name;
    public $national_id;
    public $date_of_birth;
    public $id_version_number;
    public $store_name;
    public $employee_name;
    public $weight ;
    public $karat = 21;
    public $sale_price ;
    public $product_image;
    public $showModal = false;
    public $marketPrice = 0;


    #[On('transfer-to-sales')]
  public function loadFromCalculator($weight, $karat, $price)
    {
        // 1. Assign them directly to your form variables
        $this->weight = $weight;
        $this->sale_price = $price;

        // 2. Format the Karat (e.g., "21" becomes "21K")
        $this->karat = $karat;

        $this->updateMarketPrice($this->karat);

        // 3. Open the modal
        $this->showModal = true;
    }
    public function mount()
    {
            $this->employee_name =Auth::user()->name;
            $this->store_name = Auth::user()->company_name;

        // --- NEW: Catch data from the Quick Calculator ---
        if (request()->has('weight')) {
            $this->weight = request()->query('weight');
            $this->sale_price = request()->query('sale_price ');

            // If your sales form uses "21K" instead of just "21", you can format it here:
            $karatFromUrl = request()->query('karat');
            $this->karat = $karatFromUrl ? $karatFromUrl . 'K' : '21K';

            $this->updateMarketPrice($this->karat);
        }
    }





    // This automatically runs when the Karat dropdown is changed!
    public function updatedKarat($value)
    {
        $this->updateMarketPrice($value);
    }
    private function updateMarketPrice($karatType)
    {
        // Strip the 'K' if it exists (e.g., "21K" becomes "21")
        $cleanKarat = str_replace('K', '', $karatType);

        // Cache::remember stores the result for 3600 seconds (1 hour).
        // This makes your app lightning fast and saves your API limits!
        $this->marketPrice = Cache::remember('gold_price_' . $cleanKarat, 3600, function () use ($cleanKarat) {
            try {
                // Fetch live data from the internet
                $response = Http::withHeaders([
                    'x-access-token' => env('GOLD_API_KEY'),
                    'Content-Type' => 'application/json'
                ])->get("https://www.goldapi.io/api/XAU/SAR"); // XAU = Gold, SAR = Riyals

                if ($response->successful()) {
                    $data = $response->json();

                    // Match the selected Karat to the API's data
                    if ($cleanKarat == '24') return $data['price_gram_24k'];
                    if ($cleanKarat == '22') return $data['price_gram_22k'];
                    if ($cleanKarat == '21') return $data['price_gram_21k'];
                    if ($cleanKarat == '18') return $data['price_gram_18k'];
                }

                return 0.00; // Fallback if API fails
            } catch (\Exception $e) {
                // If the internet is down, don't crash the app. Just return 0.
                return 0.00;
            }
        });
    }
 


    #[On('open-sales-form')]
    public function openModal()
    {
        $this->reset(); // Clears any old data from the last time it was opened

        $this->employee_name = Auth::user()->name;
        $this->store_name = Auth::user()->company_name;
        $this->karat = 21; // Reset default
        $this->showModal = true;
    }


    protected function rules()
    {
        return [
            'full_name'         => 'required|string|min:10',
            'national_id'       => 'required|digits:10',
            'date_of_birth'     => 'required|date|before:today',
            'id_version_number' => 'required|numeric',
            'store_name'        => 'required|string',
            'employee_name'     => 'required|string',
            'weight'            => 'required|numeric|min:0.01',
            'karat'             => 'required|integer|in:18,21,22,24',
            'sale_price'        => 'required|numeric|min:1',
            'product_image'     => 'nullable|image|max:2048',
        ];
    }

    // Calculated Property for Total
    public function getTotalProperty()
    {
        return (float)$this->weight * (float)$this->sale_price;
    }

    public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->product_image) {
            $imagePath = $this->product_image->store('products', 'public');
        }

        form::create([
            'full_name'         => $this->full_name,
            'national_id'       => $this->national_id,
            'date_of_birth'     => $this->date_of_birth,
            'id_version_number' => $this->id_version_number,
            'store_name'        => $this->store_name,
            'employee_name'     => $this->employee_name,
            'weight'            => $this->weight,
            'karat'             => $this->karat,
            'sale_price'        => $this->sale_price,
            'product_image'     => $imagePath,
        ]);

          $this->showModal = false;
        $this->dispatch('sale-added');
        session()->flash('message', 'Sale recorded successfully! | تم تسجيل البيع بنجاح');
        // return redirect()->route('sales-form.page');
    }


public $isExistingCustomer = false;

    public function updatedNationalId($value)
    {
        // 1. Only search the database if exactly 10 digits are typed
        if (strlen($value) === 10) {

            // Note: Capitalized the 'F' in Form
            $existingCustomer = form::where('national_id', $value)->first();

            if ($existingCustomer) {
                // --- CUSTOMER FOUND: Fill the inputs ---
                $this->full_name = $existingCustomer->full_name;
                $this->id_version_number = $existingCustomer->id_version_number;

                // Safely convert the database string to a Date object, then format it
                if ($existingCustomer->date_of_birth) {
                    $this->date_of_birth = \Carbon\Carbon::parse($existingCustomer->date_of_birth)->format('Y-m-d');
                }

                $this->isExistingCustomer = true;
            } else {
                // --- CUSTOMER NOT FOUND: Wipe the inputs clean ---
                $this->resetCustomerFields();
            }
        } else {
            // --- TYPING IN PROGRESS (Length != 10): Wipe the inputs clean ---
            // This prevents "ghost data" if they hit the backspace key
            $this->resetCustomerFields();
        }
    }

    // A handy helper function to quickly clear the customer data
    public function resetCustomerFields()
    {
        $this->full_name = '';
        $this->date_of_birth = '';
        $this->id_version_number = '';
        $this->isExistingCustomer = false;
    }

    public function render()
    {
        return view('livewire.sales_form');
        // ->layout('layoutscreen.app');
    }
}




