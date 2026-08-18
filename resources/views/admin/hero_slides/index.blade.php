@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Homepage Hero Slider CMS</h2>
        <p class="text-muted">Manage background images, titles, and call-to-action buttons for the storefront auto-slider</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 40px; align-items: start;">
        
        <!-- Add Slide Form -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">Add New Slide</h3>
            
            <form action="{{ route('admin.hero_slides.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="title">Slide Header / Title</label>
                    <input type="text" name="title" id="title" required class="form-control" placeholder="e.g. PERFORMANCE YOU CAN FEEL">
                </div>

                <div class="form-group">
                    <label class="form-label" for="subtitle">Subtitle / Category Badge</label>
                    <input type="text" name="subtitle" id="subtitle" class="form-control" placeholder="e.g. YOUR PREMIER SERVICE CENTER">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label class="form-label" for="button_text">Button Label</label>
                        <input type="text" name="button_text" id="button_text" required class="form-control" value="GET IN TOUCH">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="button_link">Button URL</label>
                        <input type="text" name="button_link" id="button_link" required class="form-control" value="#contact">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 15px; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="image">Background Image</label>
                        <input type="file" name="image" id="image" class="form-control" style="background-color: var(--bg-input); padding: 10px;">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="order">Sort Order</label>
                        <input type="number" name="order" id="order" required class="form-control" value="1">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px; margin-top: 25px;">
                    Save Slide <i class="fa-solid fa-save" style="margin-left: 5px;"></i>
                </button>
            </form>
        </div>

        <!-- Slides List -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">Active Slides</h3>
            
            @if($slides->isEmpty())
                <p class="text-muted" style="text-align: center; padding: 20px 0;">No hero slides added yet.</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($slides as $slide)
                        <div style="border: 1px solid var(--border-light); border-radius: 6px; padding: 20px; background-color: var(--bg-input); display: flex; gap: 20px;">
                            <img src="{{ $slide->image_path }}" alt="" style="width: 120px; height: 90px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-light); align-self: flex-start;">
                            
                            <div style="flex-grow: 1;">
                                <div class="flex-between" style="margin-bottom: 5px; align-items: start;">
                                    <div>
                                        <span class="text-primary" style="font-family: var(--font-heading); font-weight: 700; font-size: 0.8rem; text-transform: uppercase;">{{ $slide->subtitle }}</span>
                                        <h4 style="font-size: 1.15rem; text-transform: uppercase; line-height: 1.3; margin-top: 2px;">{{ $slide->title }}</h4>
                                    </div>
                                    <span style="font-weight: 700; font-size: 0.8rem; background-color: var(--border-light); padding: 2px 6px; border-radius: 4px;">Order: {{ $slide->order }}</span>
                                </div>

                                <p class="text-muted" style="font-size: 0.82rem; margin-bottom: 15px;">
                                    Button: "{{ $slide->button_text }}" &rarr; link: <span style="font-family: monospace; font-weight:600;">{{ $slide->button_link }}</span>
                                </p>

                                <div class="flex-between" style="margin-top: 10px;">
                                    <div x-data="{ active: {{ $slide->is_active ? 'true' : 'false' }} }" style="display: inline-block;">
                                        <button type="button" @click="
                                            active = !active;
                                            fetch('{{ route('admin.hero_slides.toggle_active', $slide->id) }}', {
                                                method: 'POST',
                                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                            }).then(res => {
                                                if (!res.ok) active = !active;
                                            });
                                        " class="btn btn-secondary" style="padding: 5px 12px; font-size: 0.8rem;">
                                            Status: 
                                            <span x-show="active" style="color: var(--success); font-weight: 700;">Active</span>
                                            <span x-show="!active" style="color: var(--danger); font-weight: 700;">Inactive</span>
                                        </button>
                                    </div>

                                    <div class="flex" style="gap: 10px;">
                                        <button onclick="toggleEdit('{{ $slide->id }}')" class="btn btn-secondary" style="padding: 5px 12px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        <form action="{{ route('admin.hero_slides.delete', $slide->id) }}" method="POST" onsubmit="return confirm('Delete this slide?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" style="padding: 5px 12px; font-size: 0.8rem;">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Edit form (Hidden by Default) -->
                                <form id="edit-form-{{ $slide->id }}" action="{{ route('admin.hero_slides.update', $slide->id) }}" method="POST" enctype="multipart/form-data" style="display: none; border-top: 1px solid var(--border-light); margin-top: 15px; padding-top: 15px;">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="title" required class="form-control" value="{{ $slide->title }}" placeholder="Title">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="subtitle" class="form-control" value="{{ $slide->subtitle }}" placeholder="Subtitle">
                                    </div>
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                                        <input type="text" name="button_text" required class="form-control" value="{{ $slide->button_text }}" placeholder="Button Label">
                                        <input type="text" name="button_link" required class="form-control" value="{{ $slide->button_link }}" placeholder="Button URL">
                                    </div>
                                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px; align-items: center; margin-bottom: 15px;">
                                        <input type="file" name="image" class="form-control" style="padding: 6px; font-size: 0.85rem;">
                                        <input type="number" name="order" required class="form-control" value="{{ $slide->order }}" placeholder="Order">
                                    </div>
                                    <div class="flex" style="justify-content: flex-end; gap: 10px;">
                                        <button type="button" onclick="toggleEdit('{{ $slide->id }}')" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Cancel</button>
                                        <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">Save Changes</button>
                                    </div>
                                </form>

                            </div>
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
