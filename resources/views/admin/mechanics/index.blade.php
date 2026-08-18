@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Workshop Staff Manager</h2>
        <p class="text-muted">Manage specialist mechanics profiles shown on storefront</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 40px; align-items: start;">
        
        <!-- Add Staff Form -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 8px;">Add Mechanic</h3>
            
            <form action="{{ route('admin.mechanics.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" name="name" id="name" required class="form-control" placeholder="e.g. Vance Ryder">
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">Role / Position</label>
                    <input type="text" name="role" id="role" required class="form-control" placeholder="e.g. Master Engine Tuner">
                </div>

                <div class="form-group">
                    <label class="form-label" for="bio">Biography</label>
                    <textarea name="bio" id="bio" required class="form-control" placeholder="Write experience detail and background story..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="specialties">Specialties (Comma Separated)</label>
                    <input type="text" name="specialties" id="specialties" required class="form-control" placeholder="e.g. Vintage Rebuilds, Chassis Fabrication, ECU Tuning">
                </div>

                <div class="form-group">
                    <label class="form-label" for="avatar">Avatar Image</label>
                    <input type="file" name="avatar" id="avatar" class="form-control" style="background-color: var(--bg-input); padding: 10px;">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px; margin-top: 10px;">
                    Save Mechanic <i class="fa-solid fa-save" style="margin-left: 5px;"></i>
                </button>
            </form>
        </div>

        <!-- Mechanics List -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 8px;">Current Staff</h3>
            
            @if($mechanics->isEmpty())
                <p class="text-muted" style="text-align: center; padding: 20px 0;">No mechanics added yet.</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($mechanics as $mechanic)
                        <div style="border: 1px solid var(--border-dark); border-radius: 4px; padding: 20px; background-color: var(--bg-input); display: flex; gap: 20px;">
                            <img src="{{ $mechanic->avatar_path }}" alt="" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-dark); align-self: flex-start;">
                            
                            <div style="flex-grow: 1;">
                                <div class="flex-between" style="margin-bottom: 5px;">
                                    <h4 style="font-size: 1.25rem; text-transform: uppercase;">{{ $mechanic->name }}</h4>
                                    <span class="text-primary" style="font-family: var(--font-heading); font-weight: 700; font-size: 0.95rem; text-transform: uppercase;">{{ $mechanic->role }}</span>
                                </div>

                                <p class="text-muted" style="font-size: 0.85rem; line-height: 1.4; margin-bottom: 10px;">
                                    {{ $mechanic->bio }}
                                </p>

                                <div class="flex-between" style="align-items: flex-end;">
                                    <div class="flex" style="gap: 5px; flex-wrap: wrap;">
                                        @if(is_array($mechanic->specialties))
                                            @foreach($mechanic->specialties as $spec)
                                                <span style="font-size: 0.7rem; background-color: var(--bg-light); border: 1px solid var(--border-light); padding: 2px 8px; border-radius: 12px; color: var(--text-dark); font-weight: 600;">{{ $spec }}</span>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="flex" style="gap: 5px;">
                                        <div x-data="{ active: {{ $mechanic->is_active ? 'true' : 'false' }} }" style="display: inline-block;">
                                            <button type="button" @click="
                                                active = !active;
                                                fetch('{{ route('admin.mechanics.toggle_active', $mechanic->id) }}', {
                                                    method: 'POST',
                                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                                }).then(res => {
                                                    if (!res.ok) active = !active;
                                                });
                                            " class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.75rem;">
                                                <span x-show="active" style="color: var(--success); font-weight: 700;">Active</span>
                                                <span x-show="!active" style="color: var(--danger); font-weight: 700;">Inactive</span>
                                            </button>
                                        </div>

                                        <button onclick="toggleEdit('{{ $mechanic->id }}')" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.75rem;">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.mechanics.delete', $mechanic->id) }}" method="POST" onsubmit="return confirm('Delete this profile?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.75rem;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Edit form (Hidden by Default) -->
                                <form id="edit-form-{{ $mechanic->id }}" action="{{ route('admin.mechanics.update', $mechanic->id) }}" method="POST" enctype="multipart/form-data" style="display: none; border-top: 1px solid var(--border-dark); margin-top: 15px; padding-top: 15px;">
                                    @csrf
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px;">
                                        <input type="text" name="name" required class="form-control" value="{{ $mechanic->name }}">
                                        <input type="text" name="role" required class="form-control" value="{{ $mechanic->role }}">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="bio" required class="form-control" style="min-height: 80px;">{{ $mechanic->bio }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="specialties" required class="form-control" value="{{ is_array($mechanic->specialties) ? implode(', ', $mechanic->specialties) : '' }}">
                                    </div>
                                    <div class="form-group flex-between">
                                        <input type="file" name="avatar" class="form-control" style="width: 70%; padding: 6px; font-size: 0.85rem;">
                                        <div class="flex" style="gap: 10px;">
                                            <button type="button" onclick="toggleEdit('{{ $mechanic->id }}')" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Cancel</button>
                                            <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">Save</button>
                                        </div>
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
