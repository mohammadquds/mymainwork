<div>
    @if($showModal)
<div class="max-w-4xl mx-auto py-10" dir="rtl">
 <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm transition-all overflow-y-auto" dir="rtl">

            <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh] overflow-y-auto relative mt-10">

                <form wire:submit.prevent="save">
                    <div class="bg-gray-800 p-6 flex justify-between items-center sticky top-0 z-10">
                        <h2 class="text-xl font-bold text-white">تسجيل عملية شراء جديدة</h2>
                        <button type="button" wire:click="$set('showModal', false)" class="text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

        <div class="p-8 space-y-6">
           <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-bold text-gray-700">الهوية (10 أرقام)</label>
        <input type="text" wire:model.live="national_id" maxlength="10"
               class="w-full mt-1 border border-black rounded-lg shadow-sm">
                @if($isExistingCustomer)
                    <span class="text-[10px] text-indigo-600 font-bold">بيانات مسجلة مسبقاً لهذا الرقم</span>
                @endif
               @error('national_id') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700">الاسم الثلاثي</label>
        <input type="text" wire:model="full_name"
               @if($isExistingCustomer) disabled @endif
               class="w-full mt-1 border border-black rounded-lg shadow-sm {{ $isExistingCustomer ? 'bg-gray-100 cursor-not-allowed pointer-events-none opacity-70' : '' }}">
               @error('full_name') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700">تاريخ الميلاد</label>
        <input type="date" wire:model="date_of_birth"
               @if($isExistingCustomer) disabled @endif
               class="w-full mt-1 border border-black rounded-lg shadow-sm {{ $isExistingCustomer ? 'bg-gray-100 cursor-not-allowed pointer-events-none opacity-70' : '' }}">
               @error('date_of_birth') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700">رقم نسخة الهوية</label>
        <input type="text" wire:model="id_version_number"
               @if($isExistingCustomer) disabled @endif
               class="w-full mt-1 border border-black rounded-lg shadow-sm {{ $isExistingCustomer ? 'bg-gray-100 cursor-not-allowed pointer-events-none opacity-70' : '' }}">
        @error('id_version_number') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
    </div>
</div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 p-4 rounded-xl">

                <div>
                    <label class="block text-sm font-bold text-gray-700">العيار</label>
                    <select wire:model.live="karat" class="w-full mt-1 border border-black rounded-lg shadow-sm">
                        <option value="18">18K</option>
                        <option value="21">21K</option>
                        <option value="22">22K</option>
                        <option value="24">24K</option>
                    </select>
                    @error('karat') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>


                <div>
                    <label class="block text-sm font-bold text-gray-700">الوزن (جرام)</label>
                    <input type="number" step="0.01" wire:model.live="weight" class="w-full mt-1 border border-black rounded-lg shadow-sm">
                    @error('weight') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>



                <div>
                    <label class="block text-sm font-bold text-gray-700">سعر الشراء (للجرام)</label>
                    <input type="number" step="0.01" wire:model.live="sale_price" class="w-full mt-1 border border-black rounded-lg shadow-sm">

                        <div class="mt-1.5 flex items-center gap-1 text-[11px] text-gray-500">
                            <svg class="w-3.5 h-3.5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>سعر الشاشة اليوم:</span>
                            <span class="font-bold text-yellow-600 tracking-wide">{{ number_format($marketPrice, 2) }} SAR</span>
                        </div>
                    @error('sale_price') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>


            </div>

            <div class="flex justify-between items-center p-4 bg-indigo-600 rounded-xl text-white">
                <span class="font-bold text-lg">إجمالي المبلغ:</span>
                <span class="text-2xl font-black">{{ number_format($this->total, 2) }} SAR</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
               <label class="block text-sm font-bold text-gray-700">اسم المحل / الموظف</label>
            <div class="grid grid-cols-2 gap-2 mt-1">
                <div>
                    <input type="text" wire:model="store_name" placeholder="المحل" class="w-full border border-black rounded-lg shadow-sm">
                    @error('store_name') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <input type="text" wire:model="employee_name" placeholder="الموظف" class="w-full border border-black rounded-lg shadow-sm">
                    @error('employee_name') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>
                </div>

             <div x-data="cameraHandler()">
                   @error('product_image') <span class="text-xs text-red-600 font-bold block mt-1">{{ $message }}</span> @enderror
        <label class="block text-sm font-bold text-gray-700">صورة المنتج</label>

        <div class="flex gap-2 mt-1">
            <input type="file" id="fileInput" wire:model="product_image" class="hidden">
            <label for="fileInput" class="cursor-pointer bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg text-xs font-bold transition">
                اختر ملف
            </label>

            <button type="button" @click="startCamera()" class="bg-indigo-100 hover:bg-indigo-200 text-indigo-700 px-4 py-2 rounded-lg text-xs font-bold transition">
                فتح الكاميرا
            </button>
        </div>

        <div x-show="showCamera" class="mt-4 p-2 bg-black rounded-xl overflow-hidden relative shadow-2xl">
            <video x-ref="video" autoplay playsinline class="w-full h-auto rounded-lg"></video>
            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                <button type="button" @click="takePhoto()" class="bg-green-600 text-white px-4 py-2 rounded-full font-bold shadow-lg">التماط صورة</button>
                <button type="button" @click="stopCamera()" class="bg-red-600 text-white px-4 py-2 rounded-full font-bold shadow-lg">إلغاء</button>
            </div>
            <canvas x-ref="canvas" class="hidden"></canvas>
        </div>

        <div wire:loading wire:target="product_image" class="text-blue-500 text-xs mt-2 font-bold italic">جاري الرفع...</div>

        @if ($product_image)
            <div class="mt-2">
                <img src="{{ $product_image->temporaryUrl() }}" class="w-24 h-24 rounded-xl shadow border-2 border-white">
            </div>
        @endif
    </div>

            </div>
        </div>

     <div class="bg-gray-50 px-8 py-4 flex justify-end gap-4 border-t sticky bottom-0 z-10">
                        <button type="button" wire:click="$set('showModal', false)" class="text-gray-500 font-bold px-6 py-2 hover:bg-gray-200 rounded-lg">إلغاء</button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-10 py-2 rounded-lg shadow-lg transition-all">
                            حفظ العملية
                        </button>
                    </div>
                </form>

</div>
@endif
<script>
function cameraHandler() {
    return {
        showCamera: false,
        stream: null,

        async startCamera() {
            this.showCamera = true;
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: "environment" }, // This opens the BACK camera
                    audio: false
                });
                this.$refs.video.srcObject = this.stream;
            } catch (err) {
                alert("تأكد من إعطاء صلاحية الكاميرا للمتصفح");
                this.showCamera = false;
            }
        },

        takePhoto() {
            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob((blob) => {
                const file = new File([blob], "photo.jpg", { type: "image/jpeg" });

                // This magic line sends the photo to your $product_image property
                @this.upload('product_image', file, (success) => {
                    this.stopCamera();
                });
            }, 'image/jpeg', 0.8);
        },

        stopCamera() {
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
            }
            this.showCamera = false;
        }
    }
}
</script>
</div>

