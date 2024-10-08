<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class LegalInfoContent extends Model
{
    use HasFactory, Translatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Translatable properties.
     */
    protected $translatable = ['subtitle', 'content'];
    protected $casts = [
        'subtitle' => 'array', // Cast le champ translations en tableau
        'content' => 'array', // Cast le champ translations en tableau
    ];
    
    /**
     * ONE-TO-MANY
     * One legal info title for several legal infos contents
     */
    public function legal_info_title()
    {
        return $this->belongsTo(LegalInfoTitle::class);
    }
}
