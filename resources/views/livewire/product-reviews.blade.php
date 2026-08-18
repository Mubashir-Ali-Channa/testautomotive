<div style="margin-top: 50px; border-top: 1px solid var(--border-light); padding-top: 40px;">
    
    <div class="flex-between" style="margin-bottom: 30px;">
        <h3 style="font-size: 1.8rem; text-transform: uppercase;">Customer Reviews</h3>
        <div class="flex" style="gap: 10px;">
            <div class="testimonial-rating" style="font-size: 1.2rem; margin-bottom: 0;">
                @php
                    $avg = $product->average_rating;
                    $fullStars = floor($avg);
                    $halfStar = ($avg - $fullStars) >= 0.5 ? 1 : 0;
                    $emptyStars = 5 - $fullStars - $halfStar;
                @endphp
                @for($i = 0; $i < $fullStars; $i++)
                    <i class="fa-solid fa-star"></i>
                @endfor
                @if($halfStar)
                    <i class="fa-solid fa-star-half-stroke"></i>
                @endif
                @for($i = 0; $i < $emptyStars; $i++)
                    <i class="fa-regular fa-star"></i>
                @endfor
            </div>
            <strong style="font-size: 1.1rem; color: var(--text-dark);">{{ $avg }} / 5.0</strong>
            <span class="text-muted">({{ $approvedReviews->count() }} approved)</span>
        </div>
    </div>

    <!-- Approved Reviews List -->
    <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 40px;">
        @if($approvedReviews->isEmpty() && !$myPendingReview)
            <div class="card" style="padding: 40px; text-align: center; background-color: var(--bg-white);">
                <p class="text-muted">No reviews yet. Be the first to review this product!</p>
            </div>
        @else
            <!-- Display current user's pending review first -->
            @if($myPendingReview)
                <div class="card" style="padding: 25px; border-color: var(--primary); background-color: rgba(255,46,46,0.01);">
                    <div class="flex-between" style="margin-bottom: 10px;">
                        <div>
                            <strong style="text-transform: uppercase; font-size: 1rem;">{{ $myPendingReview->user->name }}</strong>
                            <span class="badge badge-pending" style="margin-left: 10px;">Pending Approval</span>
                        </div>
                        <span class="text-muted" style="font-size: 0.85rem;">{{ $myPendingReview->updated_at->diffForHumans() }}</span>
                    </div>

                    @if($editingReviewId === $myPendingReview->id)
                        <!-- Edit Form Inline -->
                        <div style="margin-top: 15px;">
                            <div class="form-group">
                                <label class="form-label">Edit Rating</label>
                                <select wire:model="editRating" class="form-control" style="max-width: 150px;">
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Edit Comment</label>
                                <textarea wire:model="editComment" class="form-control" rows="3"></textarea>
                                @error('editComment') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex" style="gap: 10px;">
                                <button type="button" wire:click="saveEdit" class="btn btn-primary" style="padding: 6px 15px; font-size: 0.85rem;">Save Changes</button>
                                <button type="button" wire:click="cancelEdit" class="btn btn-secondary" style="padding: 6px 15px; font-size: 0.85rem;">Cancel</button>
                            </div>
                        </div>
                    @else
                        <div class="testimonial-rating" style="margin-bottom: 10px; font-size: 0.95rem;">
                            @for($i = 0; $i < $myPendingReview->rating; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            @for($i = 0; $i < (5 - $myPendingReview->rating); $i++)
                                <i class="fa-regular fa-star"></i>
                            @endfor
                        </div>
                        <p class="testimonial-text" style="margin-bottom: 15px; font-style: normal; color: var(--text-dark);">
                            "{{ $myPendingReview->comment }}"
                        </p>
                        <div class="flex" style="gap: 10px;">
                            <button type="button" wire:click="startEdit({{ $myPendingReview->id }})" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">
                                <i class="fa-solid fa-pen"></i> Edit Review
                            </button>
                            <button type="button" wire:click="deleteReview({{ $myPendingReview->id }})" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.8rem;" onclick="return confirm('Delete your review?');">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Approved reviews -->
            @foreach($approvedReviews as $rev)
                <div class="card" style="padding: 25px; background-color: var(--bg-white);">
                    <div class="flex-between" style="margin-bottom: 10px;">
                        <strong style="text-transform: uppercase; font-size: 1rem;">{{ $rev->user->name }}</strong>
                        <span class="text-muted" style="font-size: 0.85rem;">{{ $rev->created_at->diffForHumans() }}</span>
                    </div>

                    @if($editingReviewId === $rev->id)
                        <!-- Edit Form Inline -->
                        <div style="margin-top: 15px;">
                            <div class="form-group">
                                <label class="form-label">Edit Rating</label>
                                <select wire:model="editRating" class="form-control" style="max-width: 150px;">
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Edit Comment</label>
                                <textarea wire:model="editComment" class="form-control" rows="3"></textarea>
                                @error('editComment') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex" style="gap: 10px;">
                                <button type="button" wire:click="saveEdit" class="btn btn-primary" style="padding: 6px 15px; font-size: 0.85rem;">Save Changes</button>
                                <button type="button" wire:click="cancelEdit" class="btn btn-secondary" style="padding: 6px 15px; font-size: 0.85rem;">Cancel</button>
                            </div>
                        </div>
                    @else
                        <div class="testimonial-rating" style="margin-bottom: 10px; font-size: 0.95rem;">
                            @for($i = 0; $i < $rev->rating; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                            @for($i = 0; $i < (5 - $rev->rating); $i++)
                                <i class="fa-regular fa-star"></i>
                            @endfor
                        </div>
                        <p class="testimonial-text" style="margin-bottom: 15px; font-style: normal; color: var(--text-dark);">
                            "{{ $rev->comment }}"
                        </p>
                        
                        <!-- Allow user to manage their own approved reviews -->
                        @if(auth()->check() && $rev->user_id === auth()->id())
                            <div class="flex" style="gap: 10px;">
                                <button type="button" wire:click="startEdit({{ $rev->id }})" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">
                                    <i class="fa-solid fa-pen"></i> Edit Review
                                </button>
                                <button type="button" wire:click="deleteReview({{ $rev->id }})" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.8rem;" onclick="return confirm('Delete your review?');">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <!-- Review Form -->
    <div class="card" style="padding: 35px; background-color: var(--bg-white);">
        <h4 style="font-size: 1.3rem; text-transform: uppercase; margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 8px;">Write A Review</h4>
        
        @auth
            @php
                $hasReviewed = \App\Models\Review::where('product_id', $product->id)->where('user_id', auth()->id())->exists();
            @endphp

            @if($hasReviewed)
                <div class="alert alert-success" style="margin-bottom: 0;">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>You have already reviewed this product. You can modify or delete your review in the list above.</div>
                </div>
            @else
                <form wire:submit.prevent="submitReview">
                    <div class="form-group">
                        <label class="form-label" for="new-rating">Rating</label>
                        <select id="new-rating" wire:model="rating" class="form-control" style="max-width: 200px;">
                            <option value="5">5 Stars - Excellent</option>
                            <option value="4">4 Stars - Very Good</option>
                            <option value="3">3 Stars - Good</option>
                            <option value="2">2 Stars - Fair</option>
                            <option value="1">1 Star - Poor</option>
                        </select>
                        @error('rating') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="new-comment">Your Comment</label>
                        <textarea id="new-comment" wire:model="comment" class="form-control" rows="4" placeholder="Share your experience with this item..."></textarea>
                        @error('comment') <span class="text-danger" style="font-size: 0.85rem;">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                        Submit Review <i class="fa-solid fa-paper-plane" style="margin-left: 5px;"></i>
                    </button>
                </form>
            @endif
        @else
            <div style="text-align: center; padding: 15px 0;">
                <p class="text-muted" style="margin-bottom: 15px;">You must be logged in to leave a review.</p>
                <a href="{{ route('login') }}" class="btn btn-secondary">
                    Log In to Review <i class="fa-solid fa-right-to-bracket" style="margin-left: 5px;"></i>
                </a>
            </div>
        @endauth
    </div>

</div>
