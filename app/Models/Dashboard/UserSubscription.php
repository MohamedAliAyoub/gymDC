<?php

namespace App\Models\Dashboard;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $client_id
 * @property int $sale_id
 * @property int $packages_type
 * @property int $duration
 * @property float $paid_amount
 * @property string|null $whatsapp_group_link
 */
class UserSubscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'sale_id',
        'packages_type',
        'duration',
        'paid_amount',
        'whatsapp_group_link'
    ];


    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sale_id');
    }

}
