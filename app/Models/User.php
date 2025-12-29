<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    // Relationships
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function projectRequests(): HasMany
    {
        return $this->hasMany(ProjectRequest::class);
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(Download::class);
    }

    public function ticketPurchases(): HasMany
    {
        return $this->hasMany(TicketPurchase::class);
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    // Helper Methods
    public function hasActivePrioritySupport(): bool
    {
        return $this->subscriptions()
            ->whereHas('servicePlan', fn($q) => $q->where('plan_type', 'priority_support'))
            ->where('status', 'active')
            ->where('current_period_end', '>', now())
            ->exists();
    }

    public function getRemainingQuota(string $planType, string $limitKey): int
    {
        $sub = $this->subscriptions()
            ->whereHas('servicePlan', fn($q) => $q->where('plan_type', $planType))
            ->where('status', 'active')
            ->first();
        
        if (!$sub) return 0;
        
        $usedKey = str_replace('_per_month', '_used', $limitKey);
        return max(0, ($sub->servicePlan->limits[$limitKey] ?? 0) - ($sub->usage_data[$usedKey] ?? 0));
    }

    public function hasCompletedOrderForProduct(int $productId): bool
    {
        return $this->orders()
            ->where('product_id', $productId)
            ->where('status', 'completed')
            ->exists();
    }
}
