<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class AbandonedCartReminder extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Collection $cartItems;
    public float $cartTotal;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param Collection $cartItems
     * @param float $cartTotal
     */
    public function __construct(User $user, Collection $cartItems, float $cartTotal)
    {
        $this->user = $user;
        $this->cartItems = $cartItems;
        $this->cartTotal = $cartTotal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Don\'t forget your items!')
            ->view('emails.abandoned-cart')
            ->with([
                'user' => $this->user,
                'cartItems' => $this->cartItems,
                'cartTotal' => $this->cartTotal,
            ]);
    }
}

