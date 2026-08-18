@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Workshop Services Manager</h2>
        <p class="text-muted">Edit services list and price estimates shown on storefront</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 40px; align-items: start;">
        
        <!-- Add Service Form -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 8px;">Add Service</h3>
            
            <form action="{{ route('admin.services.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="title">Service Title</label>
                    <input type="text" name="title" id="title" required class="form-control" placeholder="e.g. Fork Seal Rebuild">
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea name="description" id="description" required class="form-control" placeholder="Explain what the service covers, time duration..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="price">Starting Price ($)</label>
                    <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="Leave empty for quote">
                </div>

                <div class="form-group">
                    <label class="form-label" for="icon">FontAwesome Icon Class</label>
                    <input type="text" name="icon" id="icon" class="form-control" placeholder="e.g. fa-wrench (defaults to fa-cog)">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px; margin-top: 10px;">
                    Save Service <i class="fa-solid fa-save" style="margin-left: 5px;"></i>
                </button>
            </form>
        </div>

        <!-- Services Listing -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 8px;">Active Services</h3>
            
            @if($services->isEmpty())
                <p class="text-muted" style="text-align: center; padding: 20px 0;">No services added yet.</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    @foreach($services as $service)
                        <div style="border: 1px solid var(--border-dark); border-radius: 4px; padding: 20px; background-color: var(--bg-input);">
                            <div class="flex-between" style="margin-bottom: 8px;">
                                <div class="flex" style="gap: 12px;">
                                    <div class="text-primary" style="font-size: 1.5rem;"><i class="fa-solid {{ $service->icon }}"></i></div>
                                    <h4 style="font-size: 1.25rem; text-transform: uppercase;">{{ $service->title }}</h4>
                                </div>
                                <span style="font-weight: 700; color: var(--primary);">
                                    {{ $service->price ? '$' . number_format($service->price, 2) : 'Custom Quote' }}
                                </span>
                            </div>
                            
                            <p class="text-muted" style="font-size: 0.9rem; line-height: 1.5; margin-bottom: 15px;">
                                {{ $service->description }}
                            </p>

                            <!-- Edit & Delete Row -->
                            <div class="flex-between" style="border-top: 1px solid var(--border-light); padding-top: 15px; margin-top: 15px;">
                                <div x-data="{ active: {{ $service->is_active ? 'true' : 'false' }} }">
                                    <button type="button" @click="
                                        active = !active;
                                        fetch('{{ route('admin.services.toggle_active', $service->id) }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                        }).then(res => {
                                            if (!res.ok) active = !active;
                                        });
                                    " style="background: none; border: none; cursor: pointer; padding: 0;">
                                        <template x-if="active">
                                            <span style="color: var(--success); font-weight: 600; font-size: 0.9rem;"><i class="fa-solid fa-circle-check"></i> Active</span>
                                        </template>
                                        <template x-if="!active">
                                            <span style="color: var(--text-muted); font-weight: 600; font-size: 0.9rem;"><i class="fa-solid fa-circle-xmark"></i> Inactive</span>
                                        </template>
                                    </button>
                                </div>
                                <div class="flex" style="gap: 10px;">
                                    <button onclick="toggleEdit('{{ $service->id }}')" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.services.delete', $service->id) }}" method="POST" onsubmit="return confirm('Delete this service?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Edit Box (Hidden by Default) -->
                            <form id="edit-form-{{ $service->id }}" action="{{ route('admin.services.update', $service->id) }}" method="POST" style="display: none; border-top: 1px solid var(--border-dark); margin-top: 15px; padding-top: 15px;">
                                @csrf
                                <div class="form-group">
                                    <input type="text" name="title" required class="form-control" value="{{ $service->title }}">
                                </div>
                                <div class="form-group">
                                    <textarea name="description" required class="form-control">{{ $service->description }}</textarea>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                                    <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" value="{{ $service->price }}">
                                    <input type="text" name="icon" class="form-control" placeholder="Icon Class" value="{{ $service->icon }}">
                                </div>
                                <div class="flex" style="justify-content: flex-end; gap: 10px;">
                                    <button type="button" onclick="toggleEdit('{{ $service->id }}')" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Cancel</button>
                                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">Save</button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        function toggleEdit(id) {
            var form = document.getElementById('edit-form-' + id);
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    </script>
@endsection
