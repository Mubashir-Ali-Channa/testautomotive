@extends('layouts.admin')

@section('content')

    <div class="flex-between" style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Store Inventory Catalog</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add New Product
        </a>
    </div>

    <div class="card" style="padding: 25px;">
        @if($products->isEmpty())
            <p class="text-muted" style="text-align: center; padding: 40px 0;">No products in database. Click "Add New Product" to populate your catalog.</p>
        @else
            <div class="table-responsive">
                <table class="table" style="vertical-align: middle;">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Image</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th style="text-align: center;">Stock</th>
                            <th style="text-align: center;">Featured</th>
                            <th style="text-align: center;">Active</th>
                            <th style="width: 150px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    @php
                                        $images = $product->image_paths;
                                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=300';
                                    @endphp
                                    <img src="{{ $firstImage }}" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-light);">
                                </td>
                                <td>
                                    <a href="{{ route('product.detail', $product->slug) }}" target="_blank" style="color: var(--text-dark); hover: color: var(--primary);">
                                        <strong style="font-size: 1.1rem; text-transform: uppercase;">{{ $product->name }}</strong>
                                    </a>
                                    <span style="display:block; font-size: 0.8rem; color:var(--text-muted);">Slug: {{ $product->slug }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-processing" style="font-size: 0.7rem;">{{ $product->category }}</span>
                                </td>
                                <td style="font-weight: 700; color: var(--primary);">
                                    ${{ number_format($product->price, 2) }}
                                </td>
                                <td style="text-align: center; font-weight: 600;">
                                    @if($product->stock > 0)
                                        {{ $product->stock }}
                                    @else
                                        <span style="color: var(--danger);">SOLD OUT</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <div x-data="{ featured: {{ $product->is_featured ? 'true' : 'false' }} }">
                                        <button type="button" @click="
                                            featured = !featured;
                                            fetch('{{ route('admin.products.toggle_featured', $product->id) }}', {
                                                method: 'POST',
                                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                            }).then(res => {
                                                if (!res.ok) featured = !featured;
                                            });
                                        " style="background: none; border: none; cursor: pointer; padding: 0;">
                                            <template x-if="featured">
                                                <span style="color: var(--primary); font-size: 1.2rem;"><i class="fa-solid fa-star"></i></span>
                                            </template>
                                            <template x-if="!featured">
                                                <span style="color: var(--text-muted); font-size: 1.2rem;"><i class="fa-regular fa-star"></i></span>
                                            </template>
                                        </button>
                                    </div>
                                </td>
                                <td style="text-align: center;">
                                    <div x-data="{ active: {{ $product->is_active ? 'true' : 'false' }} }">
                                        <button type="button" @click="
                                            active = !active;
                                            fetch('{{ route('admin.products.toggle_active', $product->id) }}', {
                                                method: 'POST',
                                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                            }).then(res => {
                                                if (!res.ok) active = !active;
                                            });
                                        " style="background: none; border: none; cursor: pointer; padding: 0;">
                                            <template x-if="active">
                                                <span style="color: var(--success); font-size: 1.2rem;"><i class="fa-solid fa-circle-check"></i></span>
                                            </template>
                                            <template x-if="!active">
                                                <span style="color: var(--text-muted); font-size: 1.2rem;"><i class="fa-solid fa-circle-xmark"></i></span>
                                            </template>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex" style="justify-content: center; gap: 10px;">
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.85rem;" title="Edit Product">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('admin.products.delete', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.85rem;" title="Delete Product">
                                                <i class="fa-solid fa-trash-can"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@endsection
