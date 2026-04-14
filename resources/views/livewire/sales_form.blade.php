<div x-data @fill-sales-data.window="
         $wire.set('karat', $event.detail.karat);
         $wire.set('weight', $event.detail.weight);
         $wire.set('sale_price', $event.detail.price);">

    @if($showModal)

        <div class="fixed inset-0 z-[100] bg-slate-900/80 backdrop-blur-sm" dir="rtl">
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" dir="rtl">

                {{-- MODAL CONTAINER --}}
                <form wire:submit.prevent="save" @keydown.enter.prevent=""
                    class="bg-white w-full max-w-2xl flex flex-col rounded-3xl shadow-2xl relative"
                    style="max-height: 85vh;">

                    {{-- HEADER --}}
                    <div
                        class="shrink-0 bg-slate-900 p-4 flex justify-between items-center text-white border-b border-amber-500/20 rounded-t-3xl z-20">
                        <div class="flex items-center gap-3">
                            <div class="p-1.5 bg-slate-800 rounded-lg text-amber-400 border border-slate-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <h2 class="text-base font-black text-white">تسجيل عملية شراء جديدة</h2>
                        </div>
                        <button type="button" wire:click="$set('showModal', false)"
                            onclick="document.body.style.overflow='auto'"
                            class="text-slate-400 hover:text-white bg-slate-800 p-1.5 rounded-full border border-slate-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-5 bg-slate-50/30"
                        style="-webkit-overflow-scrolling: touch;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">الهوية (10 أرقام)</label>
                                <input type="text" wire:model.live.debounce.500ms="national_id" maxlength="10"
                                    style="font-size: 16px;"
                                    class="w-full border border-slate-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm px-3 py-2.5 transition-colors">
                                @if($isExistingCustomer) <span
                                    class="text-[10px] text-indigo-600 font-bold mt-1 block">بيانات مسجلة مسبقاً</span>
                                @endif
                                @error('national_id') <span
                                class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">الاسم الثلاثي</label>
                                <input type="text" wire:model="full_name" @if($isExistingCustomer) disabled @endif
                                    style="font-size: 16px;"
                                    class="w-full border border-slate-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm px-3 py-2.5 {{ $isExistingCustomer ? 'bg-slate-100 opacity-70' : '' }}">
                                @error('full_name') <span
                                class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">رقم النسخة للهوية</label>
                                <input type="text" wire:model="id_version_number" maxlength="2" @if($isExistingCustomer)
                                disabled @endif style="font-size: 16px;"
                                    class="w-full border border-slate-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm px-3 py-2.5 {{ $isExistingCustomer ? 'bg-slate-100 opacity-70' : '' }}">
                                @error('id_version_number') <span
                                class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">تاريخ الميلاد</label>
                                <input type="date" wire:model="date_of_birth" @if($isExistingCustomer) disabled @endif
                                    style="font-size: 16px;"
                                    class="w-full border border-slate-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm px-3 py-2.5 {{ $isExistingCustomer ? 'bg-slate-100 opacity-70' : '' }}">
                                @error('date_of_birth') <span
                                class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                        </div>

                        <div
                            class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">الوزن (جرام)</label>
                                <input type="number" step="0.01" wire:model.live.debounce.500ms="weight"
                                    style="font-size: 16px;"
                                    class="w-full border border-slate-300 rounded-xl shadow-sm px-3 py-2.5">
                                @error('weight') <span
                                class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">سعر الشراء</label>
                                <input type="number" step="0.01" wire:model.live.debounce.500ms="sale_price"
                                    style="font-size: 16px;"
                                    class="w-full border border-slate-300 rounded-xl shadow-sm px-3 py-2.5">
                                @error('sale_price') <span
                                class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                                <div
                                    class="mt-1.5 flex items-center gap-1 text-[10px] text-slate-500 bg-amber-50 p-1.5 rounded-lg border border-amber-100">
                                    <span class="font-bold">السوق:</span>
                                    <span class="font-black text-amber-600">{{ number_format($marketPrice, 2) }} SAR</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">العيار</label>
                                <select wire:model.live="karat" style="font-size: 16px;"
                                    class="w-full border border-slate-300 rounded-xl shadow-sm px-3 py-2.5">
                                    <option value="18">18K</option>
                                    <option value="21">21K</option>
                                    <option value="22">22K</option>
                                    <option value="24">24K</option>
                                </select>
                            </div>
                        </div>

                        <div
                            class="flex justify-between items-center p-4 bg-slate-900 rounded-xl text-white shadow-md relative overflow-hidden">
                            <span class="font-bold text-sm relative z-10">إجمالي المبلغ:</span>
                            <span class="text-xl font-black relative z-10">{{ number_format($this->total, 2) }} SAR</span>
                        </div>


                        {{-- CAMERA & IMAGE UPLOAD SECTION --}}
                        <div x-data="{
                        showCamera: false,
                        stream: null,
                        errorMessage: '',

                        async startCamera() { this.errorMessage = ''; this.showCamera = true; try { this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false }); this.$refs.video.srcObject = this.stream; } catch (err) { alert('تأكد من إعطاء صلاحية الكاميرا'); this.showCamera = false; } },

                        takePhoto() {
                            this.errorMessage = '';
                            const video = this.$refs.video; const canvas = this.$refs.canvas; canvas.width = video.videoWidth; canvas.height = video.videoHeight; canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                            canvas.toBlob((blob) => {
                                // 1. Check Camera image size (2MB limit)
                                if(blob.size > 2 * 1024 * 1024) { this.errorMessage = 'عذراً، حجم الصورة يتجاوز 2 ميجابايت.'; this.stopCamera(); return; }

                                @this.upload('product_image', new File([blob], 'photo.jpg', { type: 'image/jpeg' }), (success) => { this.stopCamera(); });
                            }, 'image/jpeg', 0.8);
                        },

                        stopCamera() { if (this.stream) this.stream.getTracks().forEach(track => track.stop()); this.showCamera = false; },

                        // 2. Check File Upload size instantly before sending to Livewire
                        checkFileSize(event) {
                            this.errorMessage = '';
                            const file = event.target.files[0];
                            if (!file) return;

                            if (file.size > 2 * 1024 * 1024) { // 2MB in bytes
                                this.errorMessage = 'عذراً، حجم الصورة يجب أن يكون أقل من 2 ميجابايت.';
                                event.target.value = ''; // Reset the input so they can try again
                                return;
                            }

                            // If size is good, manually trigger Livewire upload
                            @this.upload('product_image', file,
                                (success) => { /* Success */ },
                                (error) => { this.errorMessage = 'حدث خطأ أثناء الرفع'; }
                            );
                        }
                    }" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">

                            <label class="block text-sm font-bold text-slate-700 mb-2">صورة المنتج</label>

                            <template x-if="errorMessage">
                                <span class="text-xs text-red-600 font-bold block mb-2" x-text="errorMessage"></span>
                            </template>
                            @error('product_image') <span
                            class="text-xs text-red-600 font-bold block mb-2">{{ $message }}</span> @enderror

                            <div class="flex flex-wrap gap-2">
                                <input type="file" id="fileInput" x-on:change="checkFileSize($event)" class="hidden"
                                    accept="image/*">

                                <label for="fileInput"
                                    class="cursor-pointer bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 px-4 py-2.5 rounded-lg text-sm font-bold transition-colors flex items-center justify-center">
                                    اختر ملف </label>
                                <span @click="startCamera()"
                                    class="cursor-pointer bg-amber-100 hover:bg-amber-200 text-amber-700 border border-amber-200 px-4 py-2.5 rounded-lg text-sm font-bold transition-colors flex items-center justify-center">
                                    فتح الكاميرا</span>
                            </div>

                            <div x-show="showCamera" wire:ignore style="display: none;"
                                class="mt-3 p-2 bg-black rounded-xl overflow-hidden relative shadow-lg">
                                <video x-ref="video" autoplay playsinline class="w-full h-auto rounded-lg"></video>
                                <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-2">
                                    <span @click="takePhoto()"
                                        class="cursor-pointer bg-green-500 text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg flex items-center justify-center">التقاط</span>
                                    <span @click="stopCamera()"
                                        class="cursor-pointer bg-red-500 text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg flex items-center justify-center">إلغاء</span>
                                </div>
                                <canvas x-ref="canvas" class="hidden"></canvas>
                            </div>

                            <div wire:loading wire:target="product_image"
                                class="text-amber-600 text-xs mt-2 font-bold flex items-center gap-1.5">
                                <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                جاري الرفع...
                            </div>

                            @if ($product_image)
                                <div class="mt-3">
                                    <img src="{{ $product_image->temporaryUrl() }}"
                                        class="w-20 h-20 object-cover rounded-lg shadow-sm border border-slate-200">
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-2">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">الموظف</label>
                                <input wire:model="employee_name" type="text" readonly style="font-size: 16px;"
                                    class="w-full px-3 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">اسم المحل</label>
                                <input wire:model="store_name" type="text" readonly style="font-size: 16px;"
                                    class="w-full px-3 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-slate-500">
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER--}}
                    <div
                        class="shrink-0 bg-white p-3 sm:p-4 flex gap-2 sm:gap-3 items-center border-t border-slate-200 rounded-b-3xl z-20">
                        <button type="button" wire:click="$set('showModal', false)"
                            onclick="document.body.style.overflow='auto'"
                            class="flex-1 bg-slate-100 text-slate-700 py-3 rounded-xl text-sm font-bold border border-slate-200 shadow-sm transition-colors hover:bg-slate-200">
                            إلغاء
                        </button>
                        <button type="submit" onclick="document.body.style.overflow='auto'"
                            class="flex-1 bg-green-600 text-white font-bold py-3 rounded-xl shadow-md flex justify-center items-center gap-2 transition-colors hover:bg-green-700">
                            <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            حفظ العملية
                        </button>
                    </div>

                </form>
            </div>
    @endif
    </div>
