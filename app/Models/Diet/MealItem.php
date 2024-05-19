<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MealItem
 *
 * @property string $name
 * @property int $meal_id
 * @property int $item_id
 * @property bool $status
 *
 * @package App\Models\Diet
 */
class MealItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'meal_id',
        'item_id',
        'status',
    ];

    /**
     * Get the meal that the meal item belongs to.
     */
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    /**
     * Get the item that the meal item belongs to.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
