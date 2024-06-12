<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemDetails
 *
 * @property string $name
 * @property int $item_id
 * @property bool $status
 *
 * @package App\Models\Diet
 */
class ItemDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'item_id',
        'calories',
        'status',
    ];
    protected $hidden = [
        'pivot'
    ];

    /**
     * Get the item that the item detail belongs to.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the standard that the item detail belongs to.
     */
    public function Standard()
    {
        return $this->hasOne(Standard::class, 'item_details_id', 'id');
    }

}
