<?php

namespace App\Model;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $casts = [
        'parent_id' => 'integer',
        'position' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'home_status' => 'integer',
        'priority' => 'integer'
    ];

    public function translations()
    {
        return $this->morphMany('App\Model\Translation', 'translationable');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id')->orderBy('priority','desc');
    }

    public function childes()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('priority','desc');
    }

    public function getNameAttribute($name)
    {
        if (strpos(url()->current(), '/admin') || strpos(url()->current(), '/seller')) {
            return $name;
        }

        return $this->translations[0]->value ?? $name;
    }
    public function scopePriority($query)
    {
        return $query->orderBy('priority','asc');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                if (strpos(url()->current(), '/api')){
                    return $query->where('locale', App::getLocale());
                }else{
                    return $query->where('locale', Helpers::default_lang());
                }
            }]);
        });
    }


    
    // public function products(){


    //     return $this->hasMany('App\Model\Product','category_ids','id');

    //     // $data = Product::select('category_ids');
    //     // $productsData = json_decode($data, true); // Convert JSON string to an associative array

    //     // $productIds = array_column($productsData, 'id'); // Get an array of product IDs

    //     // $products = Product::whereIn('id', $productIds)->get();


    // }
    // public function products()
    // {
    //     return $this->hasMany('App\Model\Product', 'category_ids', 'id')->with('decodeCategoryIds');
    // }
    
    // public function decodeCategoryIds()
    // {
    //     return $this->hasMany('App\Model\Product')->select(\DB::raw("JSON_UNQUOTE(JSON_EXTRACT(category_ids, '$[*].id')) as category_ids"));
    // }
    // public function products()
    // {
    //     return $this->hasMany(Product::class,'category_ids');
    // }



    public function getCategoryIdsAttribute()
    {
        $categoryIds = collect(json_decode($this->category_ids, true));

        return $categoryIds->pluck('id')->toArray();
    }
    public function products()
    {
        return Product::whereJsonContains('category_ids', [['id' => $this->id]])->get();
    }


    // public function products(){
    //     return $this->hasMany('App\Model\Product','category_ids','id')
    //                 ->where('id', 'id')
    //                 ->where('position', 1);
    // }

}
