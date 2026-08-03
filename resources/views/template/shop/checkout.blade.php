<!-- MODAL POPUP WIZARD (BACKDROP BLUR) -->
<div id="wizard-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">

    <!-- Backdrop Latar Belakang Blur -->
    <div onclick="closeWizardModal()"
        class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity duration-300"></div>

    <!-- Kartu Modal Utama -->
    <div class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden z-10 border border-slate-100 transform transition-all scale-95 opacity-0 duration-300 flex flex-col"
        id="modal-card">

        <!-- Header Modal & Stepper Indicator -->
        <div class="p-6 pb-4 border-b border-slate-100 bg-white shrink-0">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-slate-800 text-lg">Checkout Progress</h3>
                <button onclick="closeWizardModal()"
                    class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200 flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- STEPPER BAR -->
            <div class="flex items-center justify-between relative px-2">
                <!-- Progress Line background -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-slate-100 -z-10"></div>
                <div id="progress-line"
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-0 h-0.5 bg-emerald-500 -z-10 transition-all duration-300">
                </div>

                <!-- Step 1 Dot -->
                <div class="flex flex-col items-center gap-1">
                    <div id="dot-1"
                        class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center transition-colors">
                        1</div>
                    <span id="label-1" class="text-[10px] font-bold text-slate-800">Barang</span>
                </div>

                <!-- Step 2 Dot -->
                <div class="flex flex-col items-center gap-1">
                    <div id="dot-2"
                        class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center transition-colors">
                        2</div>
                    <span id="label-2" class="text-[10px] font-medium text-slate-400">Pengiriman</span>
                </div>

                <!-- Step 3 Dot -->
                <div class="flex flex-col items-center gap-1">
                    <div id="dot-3"
                        class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center transition-colors">
                        3</div>
                    <span id="label-3" class="text-[10px] font-medium text-slate-400">Pembayaran</span>
                </div>
            </div>
        </div>

        <!-- Body Modal Content (Step Sections) -->
        <div class="p-6 overflow-y-auto max-h-[60vh]">

            <!-- STEP 1: INFORMASI BARANG -->
            <div id="step-content-1" class="space-y-4">
                <h4 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Ringkasan Pesanan</h4>

                <div class="space-y-3">
                    <!-- Item 1 -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 bg-slate-50/50">
                        <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=150&auto=format&fit=crop&q=80"
                            class="w-12 h-12 rounded-xl object-cover bg-white">
                        <div class="flex-1 min-w-0">
                            <h5 class="text-xs font-bold text-slate-800 truncate">Shooting Star Body Wash</h5>
                            <p class="text-[11px] text-slate-500">Qty: 1 x Rp 159.000</p>
                        </div>
                        <span class="text-xs font-bold text-slate-800">Rp 159.000</span>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 bg-slate-50/50">
                        <img src="https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=150&auto=format&fit=crop&q=80"
                            class="w-12 h-12 rounded-xl object-cover bg-white">
                        <div class="flex-1 min-w-0">
                            <h5 class="text-xs font-bold text-slate-800 truncate">Rosy Cloud Perfume</h5>
                            <p class="text-[11px] text-slate-500">Qty: 1 x Rp 189.000</p>
                        </div>
                        <span class="text-xs font-bold text-slate-800">Rp 189.000</span>
                    </div>
                </div>

                <!-- Ringkasan Biaya -->
                <div class="bg-slate-50 p-4 rounded-2xl space-y-2 border border-slate-100 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal Produk</span>
                        <span class="font-semibold text-slate-700">Rp 348.000</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Diskon / Promo</span>
                        <span class="font-semibold text-emerald-600">- Rp 20.000</span>
                    </div>
                    <div class="border-t border-slate-200 pt-2 flex justify-between font-bold text-sm text-slate-900">
                        <span>Estimasi Total</span>
                        <span>Rp 328.000</span>
                    </div>
                </div>
            </div>

            <!-- STEP 2: INFORMASI PENGIRIMAN -->
            <div id="step-content-2" class="space-y-4 hidden">
                <h4 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Detail Alamat Pengiriman</h4>

                <form class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Nama Penerima</label>
                        <input type="text" value="Clara Ann"
                            class="w-full p-3 rounded-xl border border-slate-200 focus:outline-none focus:border-slate-800 bg-white">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="tel" value="081234567890"
                            class="w-full p-3 rounded-xl border border-slate-200 focus:outline-none focus:border-slate-800 bg-white">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Alamat Lengkap</label>
                        <textarea rows="3"
                            class="w-full p-3 rounded-xl border border-slate-200 focus:outline-none focus:border-slate-800 bg-white resize-none">Jl. Sudirman No. 42, RT 02/RW 05, Kebayoran Baru, Jakarta Selatan, 12190</textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Pilih Kurir</label>
                        <select
                            class="w-full p-3 rounded-xl border border-slate-200 focus:outline-none focus:border-slate-800 bg-white cursor-pointer">
                            <option>JNE Express - Reguler (Rp 15.000)</option>
                            <option>Sicepat - Same Day (Rp 25.000)</option>
                            <option>GoSend Instant (Rp 35.000)</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- STEP 3: METODE PEMBAYARAN -->
            <div id="step-content-3" class="space-y-4 hidden">
                <h4 class="font-bold text-sm text-slate-800 uppercase tracking-wider">Pilih Metode Pembayaran</h4>

                <div class="space-y-2.5">
                    <!-- QRIS -->
                    <label
                        class="flex items-center justify-between p-3.5 border-2 border-emerald-500 rounded-xl cursor-pointer bg-emerald-50/30 transition-all">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-lg bg-emerald-500 text-white flex items-center justify-center text-xs">
                                <i class="fa-solid fa-qrcode"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-800">QRIS / E-Wallet</p>
                                <p class="text-[10px] text-slate-500">GoPay, OVO, ShopeePay, Dana</p>
                            </div>
                        </div>
                        <input type="radio" name="wizard_payment" checked class="accent-emerald-600 w-4 h-4">
                    </label>

                    <!-- Virtual Account -->
                    <label
                        class="flex items-center justify-between p-3.5 border border-slate-200 rounded-xl cursor-pointer hover:border-slate-300 transition-all">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-building-columns"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-800">Virtual Account</p>
                                <p class="text-[10px] text-slate-500">BCA, Mandiri, BNI, BRI</p>
                            </div>
                        </div>
                        <input type="radio" name="wizard_payment" class="accent-emerald-600 w-4 h-4">
                    </label>

                    <!-- Kartu Kredit -->
                    <label
                        class="flex items-center justify-between p-3.5 border border-slate-200 rounded-xl cursor-pointer hover:border-slate-300 transition-all">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                                <i class="fa-regular fa-credit-card"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-800">Credit / Debit Card</p>
                                <p class="text-[10px] text-slate-500">Visa, Mastercard, JCB</p>
                            </div>
                        </div>
                        <input type="radio" name="wizard_payment" class="accent-emerald-600 w-4 h-4">
                    </label>
                </div>

                <div
                    class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex justify-between items-center text-xs">
                    <span class="text-slate-500">Total Pembayaran:</span>
                    <span class="font-bold text-slate-900 text-sm">Rp 343.000</span>
                </div>
            </div>

        </div>

        <!-- Footer Modal: Navigation Buttons -->
        <div class="p-6 pt-3 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center shrink-0">
            <button id="btn-prev" onclick="changeStep(-1)"
                class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-100 transition-colors hidden">
                Kembali
            </button>

            <div class="ml-auto flex items-center gap-2">
                <button id="btn-next" onclick="changeStep(1)"
                    class="bg-slate-900 hover:bg-black text-white px-6 py-2.5 rounded-xl text-xs font-semibold transition-colors flex items-center gap-2">
                    <span>Lanjut</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>

                <button id="btn-submit" onclick="processPayment()"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl text-xs font-semibold transition-colors flex items-center gap-2 hidden">
                    <span>Bayar Sekarang</span>
                    <i class="fa-solid fa-check text-[10px]"></i>
                </button>
            </div>
        </div>

    </div>

</div>

<!-- JAVASCRIPT: STEPPER & MODAL LOGIC -->
<script>
    let currentStep = 1;
    const totalSteps = 3;

    const modal = document.getElementById('wizard-modal');
    const modalCard = document.getElementById('modal-card');

    function openWizardModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalCard.classList.remove('scale-95', 'opacity-0');
            modalCard.classList.add('scale-100', 'opacity-100');
        }, 10);
        updateStepUI();
    }

    function closeWizardModal() {
        modalCard.classList.remove('scale-100', 'opacity-100');
        modalCard.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function changeStep(direction) {
        currentStep += direction;
        if (currentStep < 1) currentStep = 1;
        if (currentStep > totalSteps) currentStep = totalSteps;
        updateStepUI();
    }

    function updateStepUI() {
        // Update Visible Content
        for (let i = 1; i <= totalSteps; i++) {
            const content = document.getElementById(`step-content-${i}`);
            if (i === currentStep) {
                content.classList.remove('hidden');
            } else {
                content.classList.add('hidden');
            }
        }

        // Update Progress Line Width
        const progressLine = document.getElementById('progress-line');
        if (currentStep === 1) progressLine.style.width = '0%';
        if (currentStep === 2) progressLine.style.width = '50%';
        if (currentStep === 3) progressLine.style.width = '100%';

        // Update Dots and Labels
        for (let i = 1; i <= totalSteps; i++) {
            const dot = document.getElementById(`dot-${i}`);
            const label = document.getElementById(`label-${i}`);

            if (i <= currentStep) {
                dot.className = "w-8 h-8 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center transition-colors";
                label.className = "text-[10px] font-bold text-slate-800";
            } else {
                dot.className = "w-8 h-8 rounded-full bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center transition-colors";
                label.className = "text-[10px] font-medium text-slate-400";
            }
        }

        // Update Action Buttons Visibility
        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');
        const btnSubmit = document.getElementById('btn-submit');

        if (currentStep === 1) {
            btnPrev.classList.add('hidden');
            btnNext.classList.remove('hidden');
            btnSubmit.classList.add('hidden');
        } else if (currentStep === totalSteps) {
            btnPrev.classList.remove('hidden');
            btnNext.classList.add('hidden');
            btnSubmit.classList.remove('hidden');
        } else {
            btnPrev.classList.remove('hidden');
            btnNext.classList.remove('hidden');
            btnSubmit.classList.add('hidden');
        }
    }

    function processPayment() {
        alert('Pesanan berhasil dikonfirmasi! Mengalihkan ke gerbang pembayaran...');
        closeWizardModal();
    }
</script>