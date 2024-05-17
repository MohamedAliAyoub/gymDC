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

    /**
     * Get the standards associated with the item.
     */
    public function standards()
    {
        return $this->hasMany(Standard::class);
    }
}
