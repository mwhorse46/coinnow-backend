<?php

namespace App\Models;

use App\Models\DigitalShowImageSellerRelation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Seller extends Authenticatable
{
    use SoftDeletes;
    protected $table = 'seller';
    protected $fillable = ['user_id', 'firstname', 'lastname', 'email', 'telephone', 'password', 'store_name', 'balance', 'power', 'status', 'star_profit'];
    protected $casts = [
        'balance' => 'float',
        'power' => 'integer',
    ];
    protected $appends = ['view_counts'];
    const ACTIVE = 1;

    public static function getActivePluck()
    {
        return self::active()->get()->pluck('full_name', 'id');
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }

    public function product()
    {
        return $this->hasMany('App\Models\Product', 'seller_id', 'id');
    }

    public function notification()
    {
        return $this->hasMany('App\Models\Notification', 'seller_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'seller_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_seller_relations', 'seller_id', 'product_id')->withPivot('sale_date', 'sell_date', 'sale', 'quantity', 'id', 'updated_at', 'created_at', 'sell_price', 'origin_price', 'hourly_tax_list');
    }

    public function clans()
    {
        return $this->hasMany('App\Models\Clan', 'owner_id', 'id');
    }

    public function clan()
    {
        return $this->belongsTo('App\Models\Clan', 'clan_id', 'id');
    }

    public function questions()
    {
        return $this->belongsToMany('App\Models\SecurityQuestion', 'user_question_relations', 'seller_id', 'question_id');
    }

    public function images()
    {
        return $this->belongsToMany('App\Models\DigitalShowImage', 'digital_show_image_seller_relations', 'user_id', 'image_id')->withPivot('heart', 'view_status');
    }

    // public function contests()
    // {
    //     return $this->belongsToMany('App\Models\Contest', 'contest_star_relations', 'star_id', 'contest_id')->withPivot('investment');
    // }

    public function getViewCountsAttribute()
    {
        return DigitalShowImageSellerRelation::where('user_id', $this->id)->count();
    }
}
