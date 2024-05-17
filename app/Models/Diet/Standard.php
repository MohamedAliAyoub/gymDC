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
 *
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
        'status',
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
}
