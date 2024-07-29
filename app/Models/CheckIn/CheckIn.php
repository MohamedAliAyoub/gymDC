<?php

namespace App\Models\CheckIn;

use App\Models\BodyImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CheckIn extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['in_body_image_url'];
    protected $hidden = [ 'in_body_image'];
    protected array $appendWith = ['bodyImages'];


    public function getInBodyImageUrlAttribute(): ?string
    {
        return $this->in_body_image ? Storage::disk('public')->url($this->in_body_image) : null;
    }

    public function bodyImages(): HasMany
    {
        return $this->hasMany(BodyImage::class);
    }
}
