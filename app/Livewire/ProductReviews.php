<?php

namespace App\Livewire;

use App\Models\Review;
use App\Models\Product;
use Livewire\Component;

class ProductReviews extends Component
{
    public $product;
    public $rating = 5;
    public $comment = '';
    
    // For editing an existing review
    public $editingReviewId = null;
    public $editRating = 5;
    public $editComment = '';

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:5|max:1000',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function submitReview()
    {
        if (!auth()->check()) {
            $this->dispatch('toast', message: 'You must be logged in to write a review.', status: 'error');
            return;
        }

        $this->validate();

        $existing = Review::where('product_id', $this->product->id)
                          ->where('user_id', auth()->id())
                          ->first();

        if ($existing) {
            $this->dispatch('toast', message: 'You have already reviewed this product.', status: 'error');
            return;
        }

        Review::create([
            'product_id' => $this->product->id,
            'user_id' => auth()->id(),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'is_approved' => false,
        ]);

        $this->rating = 5;
        $this->comment = '';

        $this->dispatch('toast', message: 'Review submitted. It is pending admin approval.', status: 'success');
    }

    public function startEdit($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        if ($review->user_id !== auth()->id()) {
            return;
        }
        $this->editingReviewId = $reviewId;
        $this->editRating = $review->rating;
        $this->editComment = $review->comment;
    }

    public function cancelEdit()
    {
        $this->editingReviewId = null;
    }

    public function saveEdit()
    {
        $review = Review::findOrFail($this->editingReviewId);
        if ($review->user_id !== auth()->id()) {
            return;
        }

        $this->validate([
            'editRating' => 'required|integer|min:1|max:5',
            'editComment' => 'required|string|min:5|max:1000',
        ]);

        $review->update([
            'rating' => $this->editRating,
            'comment' => $this->editComment,
            'is_approved' => false,
        ]);

        $this->editingReviewId = null;
        $this->dispatch('toast', message: 'Review updated and is pending admin approval.', status: 'success');
    }

    public function deleteReview($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        if ($review->user_id !== auth()->id()) {
            return;
        }

        $review->delete();
        $this->dispatch('toast', message: 'Review deleted successfully.', status: 'success');
    }

    public function render()
    {
        $approvedReviews = Review::where('product_id', $this->product->id)
                                 ->where('is_approved', true)
                                 ->latest()
                                 ->get();

        $myPendingReview = auth()->check() 
            ? Review::where('product_id', $this->product->id)
                    ->where('user_id', auth()->id())
                    ->where('is_approved', false)
                    ->first()
            : null;

        return view('livewire.product-reviews', [
            'approvedReviews' => $approvedReviews,
            'myPendingReview' => $myPendingReview,
        ]);
    }
}
