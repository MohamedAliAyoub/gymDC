<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 *
 * @property string $name
 * @property bool $status
 *
 * @package App\Models\Diet
 */
class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];
    protected $hidden = [
        'pivot'
    ];

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
}
