<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * Determine if the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('orders.view');
    }

    /**
     * Determine if the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        // Users can view their own orders, or admins can view any
        return $order->user_id === $user->id || $user->can('orders.view');
    }

    /**
     * Determine if the user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->can('orders.update');
    }

    /**
     * Determine if the user can cancel the order.
     */
    public function cancel(User $user, Order $order): bool
    {
        // Users can cancel their own pending orders, or admins can cancel any
        if ($order->user_id === $user->id && in_array($order->status, ['pending', 'processing'])) {
            return true;
        }
        
        return $user->can('orders.cancel');
    }

    /**
     * Determine if the user can delete the order.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->can('orders.delete');
    }
}

