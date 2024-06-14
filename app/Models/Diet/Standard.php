<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Standard
 *
 * @property string $name
 * @property string $carbohydrate
 * @property string $protein
 * @property string $fat
 * @property int $item_id
 * @property int $item_details_id
 * @property bool $status
 * @property int $standard_type_id
 * @property string $standard_type_name
 * @property int $number
 * @package App\Models\Diet
 */
class Standard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'carbohydrate',
        'protein',
        'fat',
        'item_id',
        'item_details_id',
        'standard_type_id',
        'status',
        'number'
    ];

    protected $appends = ['standard_type_name'];
    protected $hidden = [
       'created_at' , 'updated_at'
    ];

    /**
     * Get the item that the standard belongs to.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the item detail that the standard belongs to.
     */
    public function itemDetail()
    {
        return $this->belongsTo(ItemDetails::class);
    }

    /**
     * Get the standard type that the standard belongs to.
     */
    public function standardType()
    {
        return $this->belongsTo(StandardType::class , 'standard_type_id');
    }

    /**
     * Get the standard type name attribute.
     */
    public function getStandardTypeNameAttribute()
    {
        return $this->standard_type->name ?? null;
    }

}
