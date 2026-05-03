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


    public $full_name;
    public $national_id;
    public $date_of_birth;
    public $id_version_number;
    public $store_name;
    public $employee_name;
    public $weight;
    public $karat = 21;
    public $sale_price;
    public $product_image;
    public $unit_type;
    public $description;
    public $showModal = false;
    public $marketPrice = 0;




    #[On('transfer-to-sales')]
    public function loadFromCalculator($weight, $karat, $price)
    {
        $this->weight = $weight;
        $this->sale_price = $price;
        $this->karat = $karat;
        $this->updateMarketPrice($this->karat);
        $this->showModal = true;
    }

    public function mount()
    {
        $this->employee_name = Auth::user()->name;
        $this->store_name = Auth::user()->company_name;

        if (request()->has('weight')) {
            $this->weight = request()->query('weight');
            $this->sale_price = request()->query('sale_price ');
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

    // the goldenkey api
    private function updateMarketPrice($karatType)
    {
        $cleanKarat = str_replace('K', '', $karatType);

        $this->marketPrice = Cache::remember('gold_price_' . $cleanKarat, 3600, function () use ($cleanKarat) {
            try {
                /** @var \Illuminate\Http\Client\Response $response */
                $response = Http::withHeaders([
                    'x-access-token' => env('GOLD_API_KEY'),
                    'Content-Type' => 'application/json'
                ])->get("https://www.goldapi.io/api/XAU/SAR");

                if ($response->successful()) {
                    $data = $response->json();

                    // Match the selected Karat to the API's data
                    if ($cleanKarat == '24')
                        return $data['price_gram_24k'];
                    if ($cleanKarat == '22')
                        return $data['price_gram_22k'];
                    if ($cleanKarat == '21')
                        return $data['price_gram_21k'];
                    if ($cleanKarat == '18')
                        return $data['price_gram_18k'];
                }

                return 0.00;
            } catch (\Exception $e) {
                // If the internet is down, don't crash the app. Just return 0.
                return 0.00;
            }
        });
    }



    #[On('open-sales-form')]
    public function openModal()
    {
        $this->reset();

        $this->employee_name = Auth::user()->name;
        $this->store_name = Auth::user()->company_name;
        $this->karat = 21;
        $this->showModal = true;
    }


    protected function rules()
    {
        return [
            'full_name' => 'required|string|min:10',
            'national_id' => 'required|unique:forms|digits:10|numeric',
            'date_of_birth' => 'required|date|before:today',
            'id_version_number' => 'required|numeric',
            'store_name' => 'required|string',
            'employee_name' => 'required|string',
            'weight' => 'required|numeric|min:0.01',
            'karat' => 'required|integer|in:18,21,22,24',
            'sale_price' => 'required|numeric|min:1',
            'product_image' => 'required|image|max:2048',
            'unit_type' => 'required|string',
            'description' => 'nullable|string',
        ];
    }

    // Calculates the Total
    public function getTotalProperty()
    {
        return (float) $this->weight * (float) $this->sale_price;
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

public function save()
    {
        $this->validate();

        $imagePath = null;
        if ($this->product_image) {
            $imagePath = $this->product_image->store('products', 'public');
        }


        $user = auth()->user();
        $topBossId = $user->admin_id ? $user->admin_id : $user->id;
        $topBoss = \App\Models\User::find($topBossId);

        $companyUserIds = [$topBoss->id];
        if ($topBoss->children && $topBoss->children->count() > 0) {
            $companyUserIds = array_merge($companyUserIds, $topBoss->children->pluck('id')->toArray());
        }

        // Find the highest invoice number this company has used so far
        $highestInvoice = form::whereIn('user_id', $companyUserIds)->max('invoice_number');

        // If they have one, add 1. If this is their first sale, start at 1!
        $newInvoiceNumber = $highestInvoice ? $highestInvoice + 1 : 1;




        form::create([
            'user_id' => auth()->id(),
            'invoice_number' => $newInvoiceNumber,
            'full_name' => $this->full_name,
            'national_id' => $this->national_id,
            'date_of_birth' => $this->date_of_birth,
            'id_version_number' => $this->id_version_number,
            'store_name' => $this->store_name,
            'employee_name' => $this->employee_name,
            'weight' => $this->weight,
            'karat' => $this->karat,
            'sale_price' => $this->sale_price,
            'product_image' => $imagePath,
            'unit_type' => $this->unit_type,
            'description' => $this->description,
        ]);

        $this->showModal = false;
        $this->dispatch('sale-added');
        session()->flash('message', 'Sale recorded successfully! | تم تسجيل البيع بنجاح');
    }


    // to check if the customer is existid before
    public $isExistingCustomer = false;

    public function updatedNationalId($value)
    {
        if (strlen($value) === 10) {
            $allowedIds = $this->getAllowedUserIds();
            $existingCustomer = form::where('national_id', $value)
                                    ->whereIn('user_id', $allowedIds)
                                    ->first();

            if ($existingCustomer) {
                $this->full_name = $existingCustomer->full_name;
                $this->id_version_number = $existingCustomer->id_version_number;

                if ($existingCustomer->date_of_birth) {
                    $this->date_of_birth = \Carbon\Carbon::parse($existingCustomer->date_of_birth)->format('Y-m-d');
                }

                $this->isExistingCustomer = true;
            } else {
                $this->resetCustomerFields();
            }
        } else {
            $this->resetCustomerFields();
        }
    }

    // function to quickly clear the customer data
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
