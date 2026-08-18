@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Create New Product</h2>
        <a href="{{ route('admin.products') }}" class="text-muted"><i class="fa-solid fa-arrow-left"></i> Back to Products List</a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;">
            
            <!-- Left Info Panel -->
            <div class="card" style="padding: 35px;">
                <div class="form-group">
                    <label class="form-label" for="name">Product Name</label>
                    <input type="text" name="name" id="name" required class="form-control" placeholder="e.g. Phoenix V2 Cafe Racer" value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Full Description</label>
                    <textarea name="description" id="description" required class="form-control" placeholder="Write item specifications, overview details, and styling features..."></textarea>
                </div>

                <!-- Specifications builder -->
                <div class="form-group" style="margin-top: 30px;">
                    <label class="form-label">Specifications (Key / Value)</label>
                    <div id="specs-container" style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 15px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: center;">
                            <input type="text" name="spec_keys[]" class="form-control" placeholder="Key (e.g. Engine)">
                            <input type="text" name="spec_values[]" class="form-control" placeholder="Value (e.g. 450cc Parallel-Twin)">
                            <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger" style="padding: 10px 15px; height: 44px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                    <button type="button" onclick="addSpecRow()" class="btn btn-secondary" style="padding: 8px 18px; font-size: 0.9rem;">
                        <i class="fa-solid fa-plus"></i> Add Spec Row
                    </button>
                </div>
            </div>

            <!-- Right Configurations Panel -->
            <div class="card" style="padding: 30px; display: flex; flex-direction: column; gap: 25px;">
                
                <div class="form-group">
                    <label class="form-label" for="price">Price ($)</label>
                    <input type="number" step="0.01" name="price" id="price" required class="form-control" placeholder="0.00" value="{{ old('price') }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="stock">Stock Available</label>
                    <input type="number" name="stock" id="stock" required class="form-control" placeholder="0" value="{{ old('stock', 1) }}">
                </div>

                <div class="form-group">
                    <label class="form-label" for="category">Product Category</label>
                    <select name="category" id="category" required class="form-control">
                        <option value="Motorcycles">Motorcycles</option>
                        <option value="Parts">Parts</option>
                        <option value="Gear">Gear</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="images">Product Images (Supports Multiple)</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple style="background-color: var(--bg-input); padding: 10px;">
                    <p class="text-muted" style="font-size: 0.75rem; margin-top: 5px;">Leave empty to fallback to Unsplash default placeholders.</p>
                </div>

                <div class="form-group flex" style="gap: 10px; cursor: pointer; border: 1px solid var(--border-dark); padding: 15px; border-radius: 4px; background-color: var(--bg-input);">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" style="accent-color: var(--primary); height: 18px; width: 18px;">
                    <label for="is_featured" style="cursor:pointer; font-weight: 600; font-size: 0.95rem;">Feature on Storefront</label>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 48px; margin-top: 10px;">
                    Create Product <i class="fa-solid fa-circle-check" style="margin-left: 5px;"></i>
                </button>
            </div>

        </div>
    </form>

@endsection

@section('scripts')
    <script>
        function addSpecRow() {
            var container = document.getElementById('specs-container');
            var row = document.createElement('div');
            row.style.display = 'grid';
            row.style.gridTemplateColumns = '1fr 1fr auto';
            row.style.gap = '15px';
            row.style.alignItems = 'center';
            row.style.marginBottom = '10px';
            row.innerHTML = `
                <input type="text" name="spec_keys[]" class="form-control" placeholder="Key">
                <input type="text" name="spec_values[]" class="form-control" placeholder="Value">
                <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger" style="padding: 10px 15px; height: 44px; display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-xmark"></i></button>
            `;
            container.appendChild(row);
        }
    </script>
@endsection
