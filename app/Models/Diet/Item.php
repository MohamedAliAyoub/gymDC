<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 *
 * @property string $name
 * @property bool $status
 * @property int $type
 * @property int $calories
 *
 * @package App\Models\Diet
 */
class Item extends Model
{
    use HasFactory;

    const RECIPE  = 0;
    const FOOD_ITEM = 1;
    const SUPPLEMENT = 2;




    protected $fillable = [
        'name',
        'type',
        'calories',
        'status',
    ];
    protected $hidden = [
        'pivot'
    ];

    protected $appends = ['type_label'];

    /**
     * Get the standards associated with the item.
     */
    public function standards()
    {
        return $this->hasMany(Standard::class);
    }

    public function itemDetails()
    {
        return $this->hasMany(ItemDetails::class);
    }

    /**
     * Get the standard that the item detail belongs to.
     */
    public function Standard()
    {
        return $this->hasOne(Standard::class , 'item_id' , 'id');
    }

    public function getTypeLabelAttribute()
    {
        $labels = [
            self::RECIPE => 'recipe',
            self::FOOD_ITEM => 'food item',
            self::SUPPLEMENT => 'supplement',
        ];

        return $labels[$this->type] ?? 'food item';
    }
}
