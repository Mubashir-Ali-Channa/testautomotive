@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Careers Manager CMS</h2>
        <p class="text-muted">Publish, modify, or close active job vacancies on the storefront careers page</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 40px; align-items: start;">
        
        <!-- Add Career Form -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">Add Job Position</h3>
            
            <form action="{{ route('admin.careers.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="title">Job Title</label>
                    <input type="text" name="title" id="title" required class="form-control" placeholder="e.g. Master Tuner / Fabricator">
                </div>

                <div class="form-group">
                    <label class="form-label" for="department">Department</label>
                    <input type="text" name="department" id="department" required class="form-control" placeholder="e.g. Custom Workshop">
                </div>

                <div class="form-group">
                    <label class="form-label" for="type">Job Type</label>
                    <select name="type" id="type" required class="form-control">
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Internship">Internship</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Job Description</label>
                    <textarea name="description" id="description" required class="form-control" style="min-height: 100px;" placeholder="Outline the main responsibilities..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="requirements">Job Requirements</label>
                    <textarea name="requirements" id="requirements" required class="form-control" style="min-height: 100px;" placeholder="Requirements, skills, certifications..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px; margin-top: 10px;">
                    Publish Position <i class="fa-solid fa-circle-check" style="margin-left: 5px;"></i>
                </button>
            </form>
        </div>

        <!-- Careers List Table -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">Active Postings</h3>
            
            @if($careers->isEmpty())
                <p class="text-muted" style="text-align: center; padding: 30px 0;">No job postings published yet.</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($careers as $career)
                        <div style="border: 1px solid var(--border-light); border-radius: 4px; padding: 20px; background-color: var(--bg-card); display: flex; flex-direction: column; gap: 15px;">
                            
                            <div>
                                <div class="flex-between" style="margin-bottom: 5px;">
                                    <h4 style="font-size: 1.2rem; text-transform: uppercase;">{{ $career->title }}</h4>
                                    <span class="badge badge-processing" style="font-size: 0.72rem; padding: 4px 10px; white-space: nowrap;">
                                        {{ $career->type }}
                                    </span>
                                </div>
                                <span style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">
                                    {{ $career->department }}
                                </span>
                            </div>

                            <div style="font-size: 0.9rem; line-height: 1.4; color: var(--text-dark);">
                                <strong>Description:</strong> {{ Str::limit($career->description, 120) }}
                            </div>

                            <div class="flex-between" style="align-items: center; border-top: 1px solid var(--border-light); padding-top: 15px; margin-top: 5px;">
                                <span style="font-size: 0.75rem; color: var(--text-muted);">Published: {{ $career->created_at->format('M d, Y') }}</span>
                                
                                <div class="flex" style="gap: 5px;">
                                    <!-- Storefront View Button -->
                                    <a href="{{ route('careers') }}" target="_blank" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.75rem;">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>

                                    <!-- Dynamic Active/Inactive Button -->
                                    <div x-data="{ active: {{ $career->is_active ? 'true' : 'false' }} }" style="display: inline-block;">
                                        <button type="button" @click="
                                            active = !active;
                                            fetch('{{ route('admin.careers.toggle_active', $career->id) }}', {
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

                                    <button onclick="toggleEdit('{{ $career->id }}')" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.75rem;">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.careers.delete', $career->id) }}" method="POST" onsubmit="return confirm('Delete this career posting?');">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.75rem;">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Edit form (Hidden by Default) -->
                            <form id="edit-form-{{ $career->id }}" action="{{ route('admin.careers.update', $career->id) }}" method="POST" style="display: none; border-top: 1px solid var(--border-light); margin-top: 15px; padding-top: 15px;">
                                @csrf
                                <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 15px; margin-bottom: 10px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 0.8rem;">Job Title</label>
                                        <input type="text" name="title" required class="form-control" value="{{ $career->title }}">
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label class="form-label" style="font-size: 0.8rem;">Department</label>
                                        <input type="text" name="department" required class="form-control" value="{{ $career->department }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-size: 0.8rem;">Job Type</label>
                                    <select name="type" required class="form-control">
                                        <option value="Full-time" {{ $career->type === 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                        <option value="Part-time" {{ $career->type === 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                        <option value="Contract" {{ $career->type === 'Contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="Internship" {{ $career->type === 'Internship' ? 'selected' : '' }}>Internship</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-size: 0.8rem;">Job Description</label>
                                    <textarea name="description" required class="form-control" style="min-height: 100px;">{{ $career->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" style="font-size: 0.8rem;">Job Requirements</label>
                                    <textarea name="requirements" required class="form-control" style="min-height: 100px;">{{ $career->requirements }}</textarea>
                                </div>
                                <div class="flex" style="gap: 10px; justify-content: flex-end;">
                                    <button type="button" onclick="toggleEdit('{{ $career->id }}')" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Cancel</button>
                                    <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">Save</button>
                                </div>
                            </form>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 25px; display: flex; justify-content: center;">
                    {{ $careers->links() }}
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
