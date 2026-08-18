@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Customer Testimonials Manager</h2>
        <p class="text-muted">Manage the customer feedback cards shown in the home page slider section</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 40px; align-items: start;">
        
        <!-- Add Testimonial Form -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">Add Testimonial</h3>
            
            <form action="{{ route('admin.testimonials.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Author Name</label>
                    <input type="text" name="name" id="name" required class="form-control" placeholder="e.g. Sarah Connor">
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">Author Description / Role</label>
                    <input type="text" name="role" id="role" class="form-control" placeholder="e.g. CB750 Rider / Cafe Fan">
                </div>

                <div class="form-group">
                    <label class="form-label" for="avatar_url">Avatar Image URL (Optional)</label>
                    <input type="url" name="avatar_url" id="avatar_url" class="form-control" placeholder="https://example.com/avatar.jpg">
                </div>

                <div class="form-group">
                    <label class="form-label" for="rating">Rating Stars</label>
                    <select name="rating" id="rating" required class="form-control">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="content">Review Content</label>
                    <textarea name="content" id="content" required class="form-control" style="min-height: 100px;" placeholder="Write their testimonial text..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px; margin-top: 10px;">
                    Save Testimonial <i class="fa-solid fa-check" style="margin-left: 5px;"></i>
                </button>
            </form>
        </div>

        <!-- Testimonials List Table -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">Manage Feedback</h3>
            
            <div class="table-responsive">
                <table class="table" style="vertical-align: middle;">
                    <thead>
                        <tr>
                            <th>Author</th>
                            <th>Comment</th>
                            <th style="text-align: center; width: 100px;">Rating</th>
                            <th style="text-align: center; width: 100px;">Status</th>
                            <th style="width: 100px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testimonials as $t)
                            <tr x-data="{ visible: true }" x-show="visible" x-transition>
                                <td>
                                    <div class="flex" style="gap: 10px;">
                                        @if($t->avatar_url)
                                            <img src="{{ $t->avatar_url }}" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                        @else
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--bg-light); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--text-dark); text-transform: uppercase;">
                                                {{ substr($t->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <strong style="display: block;">{{ $t->name }}</strong>
                                            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $t->role }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size: 0.85rem; max-width: 250px;">
                                    "{{ Str::limit($t->content, 80) }}"
                                </td>
                                <td style="text-align: center; font-weight: bold; color: var(--primary);">
                                    {{ $t->rating }} <i class="fa-solid fa-star" style="font-size: 0.8rem;"></i>
                                </td>
                                <td style="text-align: center;">
                                    <div x-data="{ active: {{ $t->is_active ? 'true' : 'false' }} }" style="display: inline-block;">
                                        <button type="button" @click="
                                            active = !active;
                                            fetch('{{ route('admin.testimonials.toggle_active', $t->id) }}', {
                                                method: 'POST',
                                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                            }).then(res => {
                                                if (!res.ok) active = !active;
                                            });
                                        " class="btn btn-secondary" style="padding: 4px 8px; font-size: 0.75rem;">
                                            <span x-show="active" style="color: var(--success); font-weight: 700;">Active</span>
                                            <span x-show="!active" style="color: var(--danger); font-weight: 700;">Inactive</span>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div style="text-align: center;">
                                        <button type="button" @click="
                                            if (confirm('Delete this testimonial card?')) {
                                                visible = false;
                                                fetch('{{ route('admin.testimonials.delete', $t->id) }}', {
                                                    method: 'POST',
                                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                                }).then(res => {
                                                    if (!res.ok) {
                                                        visible = true;
                                                        alert('Failed to delete testimonial.');
                                                    }
                                                });
                                            }
                                        " class="btn btn-danger" style="padding: 6px 12px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-trash-can"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $testimonials->links() }}
            </div>
        </div>

    </div>

@endsection
