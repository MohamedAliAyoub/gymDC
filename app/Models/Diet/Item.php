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

    const RECIPE = 0;
    const FOOD_ITEM = 1;
    const SUPPLEMENT = 2;


    protected $fillable = [
        'name',
        'type',
        'calories',
        'status',
        'default_id'
    ];
    protected $hidden = [
        'pivot'
    ];

    protected $appends = ['type_label'];

    /**
     * Get the standards associated with the item.
     */
    public function standards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Standard::class);
    }

    public function itemDetails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemDetails::class);
    }

    /**
     * Get the standard that the item detail belongs to.
     */
    public function Standard(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Standard::class, 'item_id', 'id');
    }

    public function getTypeLabelAttribute(): string
    {
        $labels = [
            self::RECIPE => 'recipe',
            self::FOOD_ITEM => 'food item',
            self::SUPPLEMENT => 'supplement',
        ];

        return $labels[$this->type] ?? 'food item';
    }


}
