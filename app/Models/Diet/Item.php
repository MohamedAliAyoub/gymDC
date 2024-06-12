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

    const CAPSULE = 0;
    const SINGLE = 1;
    const COMPOUND = 2;

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
            self::CAPSULE => 'Capsule',
            self::SINGLE => 'Single',
            self::COMPOUND => 'Compound',
        ];

        return isset($labels[$this->type]) ? $labels[$this->type] : 'Single';
    }
}
