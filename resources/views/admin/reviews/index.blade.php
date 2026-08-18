@extends('layouts.admin')

@section('content')

    <div style="margin-bottom: 30px;">
        <h2 style="font-size: 2rem; text-transform: uppercase;">Product Reviews Moderator</h2>
        <p class="text-muted">Approve or reject customer submitted product reviews and ratings</p>
    </div>

    <!-- Pending Reviews Card -->
    <div class="card" style="padding: 25px; margin-bottom: 40px; border-color: var(--primary); background-color: rgba(255,46,46,0.01);">
        <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px; color: var(--primary);">
            <i class="fa-solid fa-clock-rotate-left"></i> Reviews Pending Approval ({{ $pendingReviews->count() }})
        </h3>
        
        @if($pendingReviews->isEmpty())
            <p class="text-muted" style="text-align: center; padding: 20px 0;">All clear! There are no pending reviews to moderate.</p>
        @else
            <div class="table-responsive">
                <table class="table" style="vertical-align: middle;">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Submitted</th>
                            <th style="width: 220px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingReviews as $rev)
                            <tr>
                                <td>
                                    <strong style="text-transform: uppercase;">{{ $rev->product->name }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $rev->user->name }}</strong><br>
                                    <span style="font-size: 0.8rem; color: var(--text-muted);">{{ $rev->user->email }}</span>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: var(--primary);">{{ $rev->rating }} / 5</span>
                                </td>
                                <td style="max-width: 300px; font-style: italic; font-size: 0.9rem;">
                                    "{{ $rev->comment }}"
                                </td>
                                <td style="font-size: 0.85rem;">{{ $rev->created_at->diffForHumans() }}</td>
                                <td>
                                    <div class="flex" style="justify-content: center; gap: 8px;">
                                        <form action="{{ route('admin.reviews.approve', $rev->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.8rem;">
                                                <i class="fa-solid fa-circle-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reviews.delete', $rev->id) }}" method="POST" onsubmit="return confirm('Reject and delete this review?');">
                                            @csrf
                                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.8rem;">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Approved Reviews Card -->
    <div class="card" style="padding: 25px;">
        <h3 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">
            <i class="fa-solid fa-circle-check" style="color: var(--success);"></i> Approved Reviews List ({{ $approvedReviews->count() }})
        </h3>
        
        @if($approvedReviews->isEmpty())
            <p class="text-muted" style="text-align: center; padding: 20px 0;">No approved reviews yet.</p>
        @else
            <div class="table-responsive">
                <table class="table" style="vertical-align: middle;">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Approved Date</th>
                            <th style="width: 120px; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvedReviews as $rev)
                            <tr>
                                <td>
                                    <strong style="text-transform: uppercase;">{{ $rev->product->name }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $rev->user->name }}</strong>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: var(--primary);">{{ $rev->rating }} / 5</span>
                                </td>
                                <td style="max-width: 400px; font-size: 0.9rem;">
                                    "{{ $rev->comment }}"
                                </td>
                                <td style="font-size: 0.85rem;">{{ $rev->updated_at->diffForHumans() }}</td>
                                <td>
                                    <form action="{{ route('admin.reviews.delete', $rev->id) }}" method="POST" onsubmit="return confirm('Delete and revoke this review?');" style="text-align: center;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.8rem;">
                                            <i class="fa-solid fa-trash"></i> Revoke
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
