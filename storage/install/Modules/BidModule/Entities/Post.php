<?php

namespace Modules\BidModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\BookingModule\Entities\Booking;
use Modules\CategoryManagement\Entities\Category;
use Modules\ServiceManagement\Entities\Service;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;

class Post extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\BidModule\Database\factories\PostFactory::new();
    }

    /** Relations */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sub_category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id')->withoutGlobalScopes();
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(PostBid::class, 'post_id', 'id');
    }

    public function addition_instructions(): HasMany
    {
        return $this->hasMany(PostAdditionalInstruction::class, 'post_id', 'id');
    }

    public function ignored_posts(): HasMany
    {
        return $this->hasMany(IgnoredPost::class, 'post_id', 'id');
    }

    public function service_address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'service_address_id');
    }
}
