<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Review;

class ReviewPolicy
{
    /**
     * Determine if the user can view any reviews.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('reviews.view');
    }

    /**
     * Determine if the user can view the review.
     */
    public function view(User $user, Review $review): bool
    {
        return $user->can('reviews.view');
    }

    /**
     * Determine if the user can create reviews.
     */
    public function create(User $user): bool
    {
        // All authenticated customers can create reviews
        return $user->role === 'customer' || $user->hasRole('customer');
    }

    /**
     * Determine if the user can update the review.
     */
    public function update(User $user, Review $review): bool
    {
        // Users can update their own reviews, or admins can update any
        return $review->user_id === $user->id || $user->can('reviews.approve');
    }

    /**
     * Determine if the user can approve the review.
     */
    public function approve(User $user, Review $review): bool
    {
        return $user->can('reviews.approve');
    }

    /**
     * Determine if the user can delete the review.
     */
    public function delete(User $user, Review $review): bool
    {
        // Users can delete their own reviews, or admins can delete any
        return $review->user_id === $user->id || $user->can('reviews.delete');
    }
}

