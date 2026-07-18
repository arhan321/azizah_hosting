@extends('layouts.app')
@section('title', 'Buat Pesanan Katalog')

@php
// Ukuran & multiplier harga
$sizes = [
['label' => '30x40 cm', 'key' => '30x40', 'multiplier' => 1.0],
['label' => '40x50 cm', 'key' => '40x50', 'multiplier' => 1.5],
['label' => '50x60 cm', 'key' => '50x60', 'multiplier' => 2.0],
['label' => '60x80 cm', 'key' => '60x80', 'multiplier' => 2.5],
['label' => '80x100 cm', 'key' => '80x100', 'multiplier' => 3.0],
['label' => '100x120 cm', 'key' => '100x120', 'multiplier' => 4.0],
];
// Pilihan warna
$colors = ['Hitam','Putih','Emas (Gold)','Silver','Biru Dongker','Hijau','Merah','Coklat','Custom Warna'];
@endphp

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-1">Form Pemesanan Katalog</h3>
    <p class="text-muted mb-4">Pilih satu atau lebih desain, lalu lengkapi detail pesanan Anda</p>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
        @csrf
        <input type="hidden" name="order_type" value="catalog">

        <div class="row g-4">
            {{-- KIRI: Desain + Detail --}}
            <div class="col-lg-8">

                {{-- Pilih Desain --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white fw-semibold d-flex align-items-center justify-content-between"
                        style="background: linear-gradient(135deg,#16a085,#0f7a68)">
                        <span><i class="bi bi-images me-2"></i> Pilih Desain</span>
                        <span class="badge bg-warning text-dark" id="selectedCount">0 dipilih</span>
                    </div>
                    <div class="card-body">
                        {{-- Filter Kategori --}}
                        <div class="mb-3 d-flex flex-wrap gap-2" id="categoryFilter">
                            <button type="button"
                                class="btn btn-sm filter-btn {{ empty($selectedCategory) ? 'active' : '' }}"
                                class="{{ empty($selectedCategory) ? 'background:#16a085;color:#fff' : '' }}"
                                data-cat="all">
                                Semua
                            </button>
                            @foreach($categories as $cat)
                            <button
                                type="button"
                                class="btn btn-sm filter-btn {{ $selectedCategory == $cat->slug ? 'active' : 'btn-outline-secondary' }}"
                                class="{{ $selectedCategory == $cat->slug ? 'background:#16a085;color:white' : '' }}"
                                data-cat="{{ $cat->id }}">
                                {{ $cat->name }}
                            </button>
                            @endforeach
                        </div>

                        <div class="row g-3" id="designList">
                            @foreach($designs as $design)
                            <div class="col-6 col-md-4 design-item" data-cat="{{ $design->category_id ?? 'none' }}">
                                <div class="card design-card border-2 h-100"
                                    data-id="{{ $design->id }}"
                                    data-base-price="{{ $design->price }}"
                                    data-name="{{ e($design->name) }}"
                                    data-img="{{ $design->image_url ?? '' }}"
                                    style="cursor:pointer;transition:all .2s;border-color:transparent!important;overflow:hidden">

                                    {{-- Gambar --}}
                                    @if($design->image_url)
                                    <img src="{{ $design->image_url }}" class="card-img-top design-thumb"
                                        style="height:110px;object-fit:cover;transition:height .2s">
                                    @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center design-thumb"
                                        style="height:110px;transition:height .2s">
                                        <i class="bi bi-image fs-3 text-muted"></i>
                                    </div>
                                    @endif

                                    {{-- Nama & Harga --}}
                                    <div class="card-body p-2 text-center">
                                        <small class="fw-semibold d-block text-dark lh-sm mb-1">{{ $design->name }}</small>
                                        <small class="text-muted" style="font-size:.7rem">Mulai dari</small><br>
                                        <small class="text-success fw-bold price-display"
                                            data-base="{{ $design->price }}">
                                            Rp {{ number_format($design->price,0,',','.') }}
                                        </small>
                                    </div>

                                    {{-- Overlay check --}}
                                    <div class="design-check position-absolute top-0 end-0 m-1 d-none">
                                        <span class="badge rounded-pill bg-warning text-dark">
                                            <i class="bi bi-check-lg"></i>
                                        </span>
                                    </div>

                                    {{-- Options panel — INSIDE card, hidden until selected --}}
                                    <div class="design-options d-none border-top bg-white p-2"
                                        data-for="{{ $design->id }}"
                                        onclick="event.stopPropagation()">

                                        {{-- Pilih Ukuran --}}
                                        <label class="form-label small fw-semibold mb-1 mt-1">
                                            <i class="bi bi-rulers me-1 text-muted"></i>Pilih Ukuran
                                        </label>
                                        <select class="form-select form-select-sm size-select mb-2"
                                            data-id="{{ $design->id }}">
                                            @foreach($sizes as $s)
                                            <option value="{{ $s['key'] }}"
                                                data-multiplier="{{ $s['multiplier'] }}"
                                                data-label="{{ $s['label'] }}">
                                                {{ $s['label'] }} — Rp {{ number_format($design->price * $s['multiplier'],0,',','.') }}
                                            </option>
                                            @endforeach
                                        </select>

                                        {{-- Pilih Warna --}}
                                        <label class="form-label small fw-semibold mb-1">
                                            <i class="bi bi-palette me-1 text-muted"></i>Pilih Warna
                                        </label>
                                        <select class="form-select form-select-sm color-select mb-2"
                                            data-id="{{ $design->id }}">
                                            @foreach($colors as $c)
                                            <option value="{{ $c === 'Custom Warna' ? 'custom' : $c }}">{{ $c }}</option>
                                            @endforeach
                                        </select>

                                        {{-- Custom Warna --}}
                                        <div class="custom-color-wrap d-none mb-2">
                                            <input type="text"
                                                class="form-control form-control-sm custom-color-input"
                                                placeholder="Masukkan warna custom"
                                                data-id="{{ $design->id }}">
                                        </div>

                                        {{-- Qty --}}
                                        <label class="form-label small fw-semibold mb-1">
                                            <i class="bi bi-123 me-1 text-muted"></i>Jumlah
                                        </label>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary qty-minus"
                                                data-id="{{ $design->id }}"
                                                style="width:28px;height:28px;padding:0;line-height:1">−</button>
                                            <span class="fw-bold qty-val" data-id="{{ $design->id }}">1</span>
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary qty-plus"
                                                data-id="{{ $design->id }}"
                                                style="width:28px;height:28px;padding:0;line-height:1">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div id="selectedItems"></div>
                        @error('items') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Detail Pesanan --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white fw-semibold"
                        style="background: linear-gradient(135deg,#16a085,#0f7a68)">
                        <i class="bi bi-info-circle me-2"></i> Detail Pesanan
                    </div>
                    <div class="card-body row g-3">
                        {{-- Alamat --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Alamat Pengiriman / Pemasangan <span class="text-danger">*</span>
                            </label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                rows="3"
                                placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi">{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Catatan Tambahan</label>
                            <textarea name="notes" class="form-control" rows="3"
                                placeholder="Permintaan khusus untuk admin...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN: Ringkasan --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm position-sticky" style="top:80px">
                    <div class="card-header text-white fw-semibold"
                        style="background: linear-gradient(135deg,#16a085,#0f7a68)">
                        <i class="bi bi-receipt me-2"></i> Ringkasan Pesanan
                    </div>
                    <div class="card-body p-3">

                        <div id="summaryEmpty" class="text-muted small text-center py-3">
                            <i class="bi bi-bag fs-3 d-block mb-1"></i>
                            Pilih desain terlebih dahulu
                        </div>

                        <div id="summaryList" class="d-none mb-2"></div>

                        <div id="summaryTotal" class="d-none">
                            <div class="d-flex justify-content-between fw-bold border-top pt-2 fs-6">
                                <span>Total</span>
                                <span class="text-success" id="totalPrice">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mt-1" id="dpRow"
                                style="display:none!important">
                                <span>DP 50%</span>
                                <span id="dpPrice">Rp 0</span>
                            </div>

                            <div class="d-flex justify-content-between text-muted small mt-1"
                                id="remainingRow"
                                style="display:none!important">

                                <span>Sisa Pembayaran</span>

                                <span id="remainingPrice">Rp 0</span>

                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Metode Pembayaran</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_type"
                                    value="full" id="payFull"
                                    {{ old('payment_type','full') == 'full' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="payFull">
                                    <strong>Lunas</strong> — Bayar penuh
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_type"
                                    value="dp" id="payDP"
                                    {{ old('payment_type') == 'dp' ? 'checked' : '' }}>
                                <label class="form-check-label small" for="payDP">
                                    <strong>DP 50%</strong> — Bayar sisa setelah selesai
                                </label>
                            </div>
                            @error('payment_type')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary fw-semibold"
                                id="saveCartBtn" disabled onclick="saveToCart()">
                                <i class="bi bi-cart-plus me-1"></i> Simpan ke Keranjang
                            </button>
                            <button type="submit"
                                class="btn btn-success fw-semibold"
                                id="submitBtn"
                                disabled>
                                <i class="bi bi-lock me-1"></i> Konfirmasi Pesanan
                            </button>
                        </div>

                        <div class="alert alert-info small mt-3 mb-0 py-2">
                            <i class="bi bi-info-circle me-1"></i>
                            Dengan menyimpan ke keranjang, pesanan Anda bisa dilanjutkan kapan saja sebelum dikonfirmasi.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // ─── State ───────────────────────────────────────────────────────────────────
    // Map: designId => { name, basePrice, price, size, sizeLabel, color, qty, img }
    const selected = {};

    const fmtRp = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');

    function calcTotal() {
        return Object.values(selected).reduce((s, d) => s + d.price * d.qty, 0);
    }


    // ─── Render Summary ───────────────────────────────────────────────────────────
    function renderSummary() {
        const ids = Object.keys(selected);
        const empty = document.getElementById('summaryEmpty');
        const listEl = document.getElementById('summaryList');
        const totalWrap = document.getElementById('summaryTotal');
        const totalEl = document.getElementById('totalPrice');
        const dpRow = document.getElementById('dpRow');
        const dpEl = document.getElementById('dpPrice');
        const remainingRow = document.getElementById('remainingRow');
        const remainingEl = document.getElementById('remainingPrice');
        const countBadge = document.getElementById('selectedCount');
        const submitBtn = document.getElementById('submitBtn');
        const cartBtn = document.getElementById('saveCartBtn');

        const total = calcTotal();
        const isDp = document.getElementById('payDP').checked;

        if (ids.length === 0) {
            empty.classList.remove('d-none');
            listEl.classList.add('d-none');
            totalWrap.classList.add('d-none');
            submitBtn.disabled = true;
            cartBtn.disabled = true;
            countBadge.textContent = '0 dipilih';
            return;
        }

        empty.classList.add('d-none');
        listEl.classList.remove('d-none');
        totalWrap.classList.remove('d-none');
        submitBtn.disabled = false;
        cartBtn.disabled = false;

        listEl.innerHTML = ids.map(id => {
            const d = selected[id];
            const imgHtml = d.img ?
                `<img src="${d.img}" style="width:48px;height:40px;object-fit:cover;border-radius:4px" class="me-2 flex-shrink-0">` :
                `<div class="me-2 flex-shrink-0 bg-light d-flex align-items-center justify-content-center" style="width:48px;height:40px;border-radius:4px"><i class="bi bi-image text-muted"></i></div>`;
            return `<div class="d-flex align-items-start border rounded p-2 mb-2 bg-white">
            ${imgHtml}
            <div class="flex-grow-1 small">
                <div class="fw-semibold lh-sm">${d.name}</div>
                <div class="text-muted" style="font-size:.72rem">
                    Ukuran: ${d.sizeLabel || '-'}<br>
                    Warna: ${d.color || '-'}<br>
                    Jumlah: ${d.qty}
                </div>
            </div>
            <div class="text-success fw-bold small ms-1 text-nowrap">${fmtRp(d.price * d.qty)}</div>
        </div>`;
        }).join('');

        totalEl.textContent = fmtRp(total);

        if (isDp) {

            const dp = total * 0.5;
            const sisa = total - dp;

            dpRow.style.removeProperty('display');
            remainingRow.style.removeProperty('display');

            dpEl.textContent = fmtRp(dp);
            remainingEl.textContent = fmtRp(sisa);

        } else {

            dpRow.style.display = 'none';
            remainingRow.style.display = 'none';

        }

        const totalItems = Object.values(selected).reduce((s, d) => s + d.qty, 0);
        countBadge.textContent = totalItems + ' dipilih';
    }

    // ─── Build Hidden Inputs ──────────────────────────────────────────────────────
    function buildHiddenInputs() {
        let html = '';
        let idx = 0;
        Object.entries(selected).forEach(([id, d]) => {
            html += `<input type="hidden" name="items[${idx}][design_id]" value="${id}">`;
            html += `<input type="hidden" name="items[${idx}][size]" value="${d.sizeLabel || ''}">`;
            html += `<input type="hidden" name="items[${idx}][color]"     value="${d.color || ''}">`;
            html += `<input type="hidden" name="items[${idx}][qty]"       value="${d.qty}">`;
            html += `<input type="hidden" name="items[${idx}][price]"     value="${d.price}">`;
            idx++;
        });
        document.getElementById('selectedItems').innerHTML = html;
    }

    // ─── Get current options for a design card ───────────────────────────────────
    function getOptions(id) {
        // Semua elemen options sekarang ada di dalam .design-card
        const card = document.querySelector(`.design-card[data-id="${id}"]`);
        const sizeEl = card ? card.querySelector('.size-select') : null;
        const colorEl = card ? card.querySelector('.color-select') : null;
        const customEl = card ? card.querySelector('.custom-color-input') : null;
        const qtyEl = card ? card.querySelector('.qty-val') : null;

        const sizeOpt = sizeEl ? sizeEl.options[sizeEl.selectedIndex] : null;
        const multiplier = sizeOpt ? parseFloat(sizeOpt.dataset.multiplier) : 1;
        const sizeLabel = sizeOpt ? sizeOpt.dataset.label : '';
        const sizeKey = sizeOpt ? sizeOpt.value : '';
        const basePrice = card ? parseFloat(card.dataset.basePrice) : 0;
        const price = basePrice * multiplier;

        let color = colorEl ? colorEl.value : '';
        if (color === 'custom' && customEl) {
            color = customEl.value || 'Custom';
        }

        const qty = qtyEl ? parseInt(qtyEl.textContent) : 1;

        return {
            sizeKey,
            sizeLabel,
            price,
            color,
            qty
        };
    }

    // ─── Card Click (toggle select) ──────────────────────────────────────────────
    document.querySelectorAll('.design-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Jangan toggle jika klik pada options panel atau elemen di dalamnya
            if (e.target.closest('.design-options')) return;

            const id = this.dataset.id;
            const name = this.dataset.name;
            const img = this.dataset.img;
            const options = this.querySelector('.design-options');
            const checkEl = this.querySelector('.design-check');

            if (selected[id]) {
                // Deselect
                delete selected[id];
                this.style.borderColor = 'transparent';
                this.style.boxShadow = '';
                checkEl.classList.add('d-none');
                if (options) options.classList.add('d-none');
            } else {
                // Select
                const opts = getOptions(id);
                selected[id] = {
                    name,
                    img,
                    basePrice: parseFloat(this.dataset.basePrice),
                    ...opts
                };
                this.style.borderColor = '#16a085';
                this.style.boxShadow = '0 0 0 3px rgba(22,160,133,.35)';
                checkEl.classList.remove('d-none');
                if (options) options.classList.remove('d-none');
            }
            renderSummary();
            buildHiddenInputs();
        });
    });

    // ─── Size Change ─────────────────────────────────────────────────────────────
    document.querySelectorAll('.size-select').forEach(sel => {
        sel.addEventListener('change', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            if (!selected[id]) return;
            const opts = getOptions(id);
            Object.assign(selected[id], opts);
            renderSummary();
            buildHiddenInputs();
        });
    });

    // ─── Color Change ────────────────────────────────────────────────────────────
    document.querySelectorAll('.color-select').forEach(sel => {
        sel.addEventListener('change', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            const card = document.querySelector(`.design-card[data-id="${id}"]`);
            const wrap = card ? card.querySelector('.custom-color-wrap') : null;
            if (wrap) {
                wrap.classList.toggle('d-none', this.value !== 'custom');
            }
            if (!selected[id]) return;
            const opts = getOptions(id);
            Object.assign(selected[id], opts);
            renderSummary();
            buildHiddenInputs();
        });
    });

    // ─── Custom Color Input ───────────────────────────────────────────────────────
    document.querySelectorAll('.custom-color-input').forEach(inp => {
        inp.addEventListener('input', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            if (!selected[id]) return;
            const opts = getOptions(id);
            Object.assign(selected[id], opts);
            renderSummary();
            buildHiddenInputs();
        });
    });

    // ─── Qty Minus / Plus ────────────────────────────────────────────────────────
    document.querySelectorAll('.qty-minus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            const card = document.querySelector(`.design-card[data-id="${id}"]`);
            const qtyEl = card ? card.querySelector('.qty-val') : null;
            if (!qtyEl) return;
            let qty = parseInt(qtyEl.textContent);
            if (qty > 1) {
                qtyEl.textContent = --qty;
                if (selected[id]) {
                    selected[id].qty = qty;
                    renderSummary();
                    buildHiddenInputs();
                }
            }
        });
    });

    document.querySelectorAll('.qty-plus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            const card = document.querySelector(`.design-card[data-id="${id}"]`);
            const qtyEl = card ? card.querySelector('.qty-val') : null;
            if (!qtyEl) return;
            let qty = parseInt(qtyEl.textContent);
            qtyEl.textContent = ++qty;
            if (selected[id]) {
                selected[id].qty = qty;
                renderSummary();
                buildHiddenInputs();
            }
        });
    });

    // ─── DP Toggle ───────────────────────────────────────────────────────────────
    document.querySelectorAll('[name="payment_type"]').forEach(r => {
        r.addEventListener('change', renderSummary);
    });

    // ─── Category Filter ─────────────────────────────────────────────────────────
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.style.background = '';
                b.style.color = '';
                b.classList.remove('active');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('active');
            this.style.background = '#16a085';
            this.style.color = '#fff';

            const cat = this.dataset.cat;
            document.querySelectorAll('.design-item').forEach(item => {
                item.style.display = (cat === 'all' || item.dataset.cat == cat) ? '' : 'none';
            });
        });
    });

    // ─── Save to Cart (session via hidden flag) ───────────────────────────────────
    function saveToCart() {

        if (Object.keys(selected).length === 0) return;

        buildHiddenInputs();

        let flag = document.createElement('input');

        flag.type = 'hidden';
        flag.name = 'save_to_cart';
        flag.value = '1';

        document.getElementById('orderForm').appendChild(flag);

        document.getElementById('orderForm').submit();
    }

    // ─── Pre-select from ?design_id= ─────────────────────────────────────────────

    const selectedDesignId = "{{ $selectedDesignId }}";
    const selectedCategory = "{{ $selectedCategory }}";

    // Kalau datang dari halaman Detail
    if (selectedCategory) {

        const activeBtn = document.querySelector('.filter-btn.active');

        if (activeBtn) {

            activeBtn.click();

        }

    }

    // otomatis pilih card
    if (selectedDesignId) {

        const preCard = document.querySelector(
            `.design-card[data-id="${selectedDesignId}"]`
        );

        if (preCard) {

            preCard.click();

        }

    }
</script>
@endpush
@endsection