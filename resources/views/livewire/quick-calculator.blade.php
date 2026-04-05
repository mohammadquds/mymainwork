<div x-data="{
        // --- TABS STATE ---
        activeTab: 'gold', // 'gold' (A) or 'normal' (B)

        // --- OPTION A: GOLD CALCULATOR STATE ---
        weight: '',
        karat: '21',
        sale_price: '',
        cost_per_gram: '',
        discount: 0,



     // --- UPDATED: Function to send data to the Sales Form ---
        goToSaleForm() {
            // 1. Shout the numbers across the browser
            this.$dispatch('transfer-to-sales', {
                weight: this.weight,
                karat: this.karat,
                price: this.sale_price
            });

            // 2. Shout a command to close the Calculator modal
            this.$dispatch('close-calculator-modal');
        },

        get total() {
            let w = parseFloat(this.weight) || 0;
            let p = parseFloat(this.sale_price) || 0;
            let d = parseFloat(this.discount) || 0;

            let subtotal = w * p;
            let discountAmount = (d / 100) * subtotal;
            let final = subtotal - discountAmount;
            return final > 0 ? final : 0;
        },
        get profit() {
            let w = parseFloat(this.weight) || 0;
            let c = parseFloat(this.cost_per_gram) || 0;
            return this.total - (w * c);
        },
        resetGoldCalc() {
            this.weight = '';
            this.sale_price = '';
            this.cost_per_gram = '';
            this.discount = 0;
            this.karat = '21';
        },

        // --- OPTION B: NORMAL CALCULATOR STATE ---
        calcDisplay: '',
        appendCalc(char) {
            if (this.calcDisplay === 'Error') this.calcDisplay = '';

            // Prevent entering multiple operators in a row (like ++ or **)
            const lastChar = this.calcDisplay.slice(-1);
            const ops = ['+','-','*','/'];
            if (ops.includes(char) && ops.includes(lastChar)) {
                this.calcDisplay = this.calcDisplay.slice(0, -1) + char;
                return;
            }
            this.calcDisplay += char;
        },
        calculateResult() {
            try {
                if (!this.calcDisplay) return;
                // Safely evaluate the math string
                let res = new Function('return ' + this.calcDisplay)();
                if (!isFinite(res) || isNaN(res)) throw 'Error';
                // Round it to avoid weird decimal bugs (like 0.30000000004)
                this.calcDisplay = Math.round(res * 1000000) / 1000000 + '';
            } catch(e) {
                this.calcDisplay = 'Error';
            }
        },
        clearCalc() {
            this.calcDisplay = '';
        }
    }"
    dir="rtl"
    class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 w-full max-w-md mx-auto relative overflow-hidden"
>

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-extrabold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            الحاسبة
        </h3>

        <button @click="activeTab === 'gold' ? resetGoldCalc() : clearCalc()" class="text-xs text-gray-400 hover:text-red-500 transition">
            إعادة ضبط
        </button>
    </div>

    <div class="flex bg-gray-100 rounded-xl p-1 mb-6">
        <button @click="activeTab = 'gold'" :class="activeTab === 'gold' ? 'bg-white shadow-sm text-yellow-600' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2 text-sm font-bold rounded-lg transition duration-200">
            الذكية
        </button>
        <button @click="activeTab = 'normal'" :class="activeTab === 'normal' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2 text-sm font-bold rounded-lg transition duration-200">
           العادية
        </button>
    </div>

    <div x-show="activeTab === 'gold'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">العيار</label>
                    <select x-model="karat" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:bg-white transition appearance-none">
                        <option value="24">عيار 24</option>
                        <option value="22">عيار 22</option>
                        <option value="21">عيار 21</option>
                        <option value="18">عيار 18</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">الوزن (جرام)</label>
                    <input x-model="weight" type="number" step="0.01" min="0" placeholder="0.00" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">سعر الشراء (للجرام)</label>
                    <input x-model="sale_price" type="number" step="0.01" placeholder="0.00" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">التكلفة للجرام (اختياري)</label>
                    <input x-model="cost_per_gram" type="number" step="0.01" placeholder="0.00" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:bg-white transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">خصم إضافي (%)</label>
                <input x-model="discount" type="number" min="0" max="100" placeholder="0" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-800 focus:ring-2 focus:ring-yellow-500 focus:bg-white transition">
            </div>
        </div>

        <div class="mt-6 p-5 bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl border border-yellow-200">
            <div class="flex justify-between items-end mb-2">
                <span class="text-sm font-bold text-yellow-800">الإجمالي النهائي:</span>
                <span class="text-3xl font-black text-yellow-600" x-text="total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
            </div>
            <div x-show="cost_per_gram > 0" class="flex justify-between items-center mt-3 pt-3 border-t border-yellow-200/60" style="display: none;">
                <span class="text-xs font-bold text-gray-500">صافي الربح:</span>
                <span class="text-sm font-extrabold" x-bind:class="profit >= 0 ? 'text-emerald-500' : 'text-red-500'" x-text="profit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span>
            </div>
        </div>
                <div class="mt-4">
            <button @click="goToSaleForm()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-2xl font-extrabold transition-all shadow-lg flex items-center justify-center gap-2">
                <span>إنتقل إلى عملية الشراء</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </button>
        </div>
    </div>


    <div x-show="activeTab === 'normal'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">

        <div dir="ltr" class="bg-gray-50 border border-gray-200 shadow-inner rounded-xl p-4 mb-4 text-right overflow-x-auto min-h-[80px] flex items-center justify-end">
            <span class="font-mono text-3xl font-bold text-gray-800 tracking-wider" x-text="calcDisplay.replace('*', '×').replace('/', '÷') || '0'"></span>
        </div>

        <div dir="ltr" class="grid grid-cols-4 gap-3">
            <button @click="clearCalc()" class="col-span-2 bg-red-100 text-red-600 font-extrabold text-xl py-3 rounded-xl hover:bg-red-200 transition shadow-sm">C</button>
            <button @click="appendCalc('/')" class="bg-blue-100 text-blue-600 font-extrabold text-xl py-3 rounded-xl hover:bg-blue-200 transition shadow-sm">÷</button>
            <button @click="appendCalc('*')" class="bg-blue-100 text-blue-600 font-extrabold text-xl py-3 rounded-xl hover:bg-blue-200 transition shadow-sm">×</button>

            <button @click="appendCalc('7')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">7</button>
            <button @click="appendCalc('8')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">8</button>
            <button @click="appendCalc('9')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">9</button>
            <button @click="appendCalc('-')" class="bg-blue-100 text-blue-600 font-extrabold text-xl py-3 rounded-xl hover:bg-blue-200 transition shadow-sm">−</button>

            <button @click="appendCalc('4')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">4</button>
            <button @click="appendCalc('5')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">5</button>
            <button @click="appendCalc('6')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">6</button>
            <button @click="appendCalc('+')" class="bg-blue-100 text-blue-600 font-extrabold text-xl py-3 rounded-xl hover:bg-blue-200 transition shadow-sm">+</button>

            <button @click="appendCalc('1')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">1</button>
            <button @click="appendCalc('2')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">2</button>
            <button @click="appendCalc('3')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">3</button>
            <button @click="calculateResult()" class="row-span-2 bg-blue-600 text-white font-extrabold text-2xl py-3 rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-200">=</button>

            <button @click="appendCalc('0')" class="col-span-2 bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">0</button>
            <button @click="appendCalc('.')" class="bg-white border border-gray-100 text-gray-800 font-bold text-xl py-3 rounded-xl hover:bg-gray-50 transition shadow-sm">.</button>
        </div>



    </div>

</div>
