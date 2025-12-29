<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'role',
        'country_code',
        'phone',
        'date_of_birth',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function plantCareReminders()
    {
        return $this->hasMany(PlantCareReminder::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function priceAlerts()
    {
        return $this->hasMany(PriceAlert::class);
    }

    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function addPoints(int $amount, string $type, string $description = null, $orderId = null)
    {
        $this->increment('points', $amount);
        $this->pointTransactions()->create([
            'points' => $amount,
            'type' => $type,
            'description' => $description,
            'order_id' => $orderId
        ]);
    }

    public function spendPoints(int $amount, string $description = null, $orderId = null)
    {
        if ($this->points < $amount) {
            return false;
        }

        $this->decrement('points', $amount);
        $this->pointTransactions()->create([
            'points' => -$amount,
            'type' => 'redemption',
            'description' => $description,
            'order_id' => $orderId
        ]);

        return true;
    }

    public function billingAddresses()
    {
        return $this->addresses()->byType('billing');
    }

    public function shippingAddresses()
    {
        return $this->addresses()->byType('shipping');
    }

    public function defaultBillingAddress()
    {
        return $this->billingAddresses()->default()->first();
    }

    public function defaultShippingAddress()
    {
        return $this->shippingAddresses()->default()->first();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    /**
     * Get the user's loyalty points.
     */
    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
     * Get the user's current loyalty points balance.
     */
    public function getCurrentLoyaltyPointsAttribute()
    {
        return $this->loyaltyPoints()
            ->active()
            ->sum('points');
    }

    /**
     * Get the user's audit logs.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Get user's role display name.
     */
    public function getRoleDisplayAttribute()
    {
        $roles = [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'customer' => 'Customer',
            'guest' => 'Guest',
        ];

        return $roles[$this->role] ?? ucfirst($this->role);
    }
}
