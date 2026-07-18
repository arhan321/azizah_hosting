{{-- Reusable form partial for create/edit design --}}
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Nama Desain <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $design->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $design->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
            value="{{ old('price', $design->price ?? '') }}" min="0" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-8">
        <label class="form-label fw-semibold">Gambar Desain</label>

        @if(isset($design) && $design->image_url)
        <div class="mb-2">
            <img src="{{ $design->image_url }}" class="rounded border" style="height:100px;object-fit:cover">
            <small class="text-muted d-block mt-1">Upload baru untuk mengganti</small>
        </div>
        @endif

        <input
            type="file"
            id="image"
            name="image"
            class="form-control @error('image') is-invalid @enderror"
            accept=".jpg,.jpeg,.png,.webp">

        <div class="form-text">
            Format: <strong>JPG, JPEG, PNG</strong>. Maksimal ukuran file <strong>5 MB</strong>.
        </div>

        <div id="image-error" class="text-danger small mt-1 d-none">
            Ukuran gambar maksimal 5 MB.
        </div>

        @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Deskripsi</label>
        <textarea id="design-description-editor" name="description" class="form-control" rows="8"
            placeholder="Deskripsi desain kaligrafi...">{{ old('description', $design->description ?? '') }}</textarea>
        <small class="text-muted">Mendukung format rich text (judul, list, tebal, miring, link).</small>
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Spesifikasi Produk</label>

        <textarea
            id="design-specification-editor"
            name="specification"
            class="form-control"
            rows="8"
            placeholder="Masukkan spesifikasi produk...">{{ old('specification', $design->specification ?? '') }}</textarea>

        <small class="text-muted">
            Contoh: Material, Finishing, Teknik, Bingkai, Ukuran, Pemasangan.
        </small>
    </div>
</div>

@push('styles')
<style>
    .ck-editor__editable_inline {
        min-height: 220px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const editorElement = document.querySelector('#design-description-editor');

        if (editorElement && window.ClassicEditor) {
            ClassicEditor.create(editorElement, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', 'blockQuote', '|',
                    'undo', 'redo'
                ]
            }).catch(() => {});
        }

        const specificationEditor = document.querySelector('#design-specification-editor');

        if (specificationEditor && window.ClassicEditor) {
            ClassicEditor.create(specificationEditor, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', 'blockQuote', '|',
                    'undo', 'redo'
                ]
            }).catch(() => {});
        }

        // VALIDASI UKURAN GAMBAR
        const imageInput = document.getElementById('image');
        const imageError = document.getElementById('image-error');

        if (imageInput) {

            imageInput.addEventListener('change', function() {

                imageError.classList.add('d-none');

                const maxSize = 5 * 1024 * 1024; // 5 MB

                if (this.files.length > 0 && this.files[0].size > maxSize) {

                    imageError.classList.remove('d-none');
                    this.value = '';

                }

            });

        }

    });
</script>
@endpush