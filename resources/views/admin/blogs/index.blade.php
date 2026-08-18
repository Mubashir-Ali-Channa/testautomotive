@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Garage Blog Manager</h2>
        <p class="text-muted">Create, edit, or remove articles shown on storefront blog</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 40px; align-items: start;">
        
        <!-- Add Blog Article Form -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 8px;">Add Article</h3>
            
            <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="title">Article Title</label>
                    <input type="text" name="title" id="title" required class="form-control" placeholder="e.g. How to Clean Chains">
                </div>

                <div class="form-group">
                    <label class="form-label" for="category">Category</label>
                    <input type="text" name="category" id="category" required class="form-control" placeholder="e.g. Maintenance Guides" value="Tips & Guides">
                </div>

                <div class="form-group">
                    <label class="form-label" for="content">Article Content</label>
                    <textarea name="content" id="content" required class="form-control" style="min-height: 180px;" placeholder="Write full article body text..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Featured Image</label>
                    <input type="file" name="image" id="image" class="form-control" style="background-color: var(--bg-input); padding: 10px;">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; height: 44px; margin-top: 10px;">
                    Publish Article <i class="fa-solid fa-upload" style="margin-left: 5px;"></i>
                </button>
            </form>
        </div>

        <!-- Articles Grid List -->
        <div class="card" style="padding: 25px;">
            <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-dark); padding-bottom: 8px;">Published Articles</h3>
            
            @if($blogs->isEmpty())
                <p class="text-muted" style="text-align: center; padding: 20px 0;">No articles written yet.</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($blogs as $blog)
                        <div style="border: 1px solid var(--border-dark); border-radius: 4px; padding: 20px; background-color: var(--bg-input); display: flex; gap: 20px;">
                            <img src="{{ $blog->image_path }}" alt="" style="width: 100px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border-dark); align-self: flex-start;">
                            
                            <div style="flex-grow: 1;">
                                <div class="flex-between" style="margin-bottom: 5px;">
                                    <h4 style="font-size: 1.15rem; text-transform: uppercase;">{{ $blog->title }}</h4>
                                    <span class="text-primary" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">{{ $blog->category }}</span>
                                </div>

                                <p class="text-muted" style="font-size: 0.85rem; line-height: 1.4; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $blog->content }}
                                </p>

                                <div class="flex-between" style="align-items: flex-end;">
                                    <span style="font-size: 0.75rem; color: var(--text-muted);">Published: {{ $blog->created_at->format('M d, Y') }}</span>

                                    <div class="flex" style="gap: 5px;">
                                        <!-- Storefront View Button -->
                                        <a href="{{ route('blog.post', $blog->slug) }}" target="_blank" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.75rem;">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>

                                        <!-- Dynamic Active/Inactive Button -->
                                        <div x-data="{ active: {{ $blog->is_active ? 'true' : 'false' }} }" style="display: inline-block;">
                                            <button type="button" @click="
                                                active = !active;
                                                fetch('{{ route('admin.blogs.toggle_active', $blog->id) }}', {
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

                                        <button onclick="toggleEdit('{{ $blog->id }}')" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.75rem;">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.blogs.delete', $blog->id) }}" method="POST" onsubmit="return confirm('Delete this article?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.75rem;">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Edit form (Hidden by Default) -->
                                <form id="edit-form-{{ $blog->id }}" action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data" style="display: none; border-top: 1px solid var(--border-dark); margin-top: 15px; padding-top: 15px;">
                                    @csrf
                                    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 15px; margin-bottom: 10px;">
                                        <input type="text" name="title" required class="form-control" value="{{ $blog->title }}">
                                        <input type="text" name="category" required class="form-control" value="{{ $blog->category }}">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="content" required class="form-control" style="min-height: 120px;">{{ $blog->content }}</textarea>
                                    </div>
                                    <div class="form-group flex-between">
                                        <input type="file" name="image" class="form-control" style="width: 70%; padding: 6px; font-size: 0.85rem;">
                                        <div class="flex" style="gap: 10px;">
                                            <button type="button" onclick="toggleEdit('{{ $blog->id }}')" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">Cancel</button>
                                            <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">Save</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 25px; display: flex; justify-content: center;">
                    {{ $blogs->links() }}
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
