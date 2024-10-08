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
class LegalInfoSubject extends Model
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
    protected $translatable = ['subject_name', 'subject_description'];


    public function getLegalInfoTitle_title($lang = 'fr')
    {
        return $this->title[$lang] ?? null; // Retourne la description dans la langue demandée
    }
    protected $casts = [
        'subject_name' => 'array', // Cast le champ translations en tableau
        'subject_description' => 'array', // Cast le champ translations en tableau
    ];
    /**
     * MANY-TO-ONE
     * Several legal infos titles for a legal info subject
     */
    public function legal_info_titles()
    {
        return $this->hasMany(LegalInfoTitle::class);
    }
}
