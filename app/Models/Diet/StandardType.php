<?php

namespace App\Models\Diet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StandardType
 *
 * @property string $name
 * @property int $standard_id
 * @property bool $status
 *
 * @package App\Models\Diet
 */
class StandardType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'standard_id',
        'status',
    ];

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    /**
     * Get the standard that the standard type belongs to.
     */
    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
