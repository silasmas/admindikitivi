<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class Media extends Model
{
    use HasFactory;

    protected $table = 'medias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    protected $casts = [
        'published_date' => 'date',
        'tags' => 'array',
        'is_autoplay_eligible' => 'boolean',
        'is_downloadable' => 'boolean',
    ];

    /**
     * Tranches d'âge proposées dans le formulaire (même liste que l'API).
     */
    public const AGE_RANGES = [
        '3-5' => '3-5 ans',
        '6-8' => '6-8 ans',
        '9-11' => '9-11 ans',
        '12+' => '12 ans et plus',
        'all' => 'Tous les âges / Famille',
    ];

    /**
     * Étapes de la "Progression dans la foi" utilisées par le moteur de recommandation.
     */
    public const FAITH_STEPS = [
        'jesus' => 'Découvrir Jésus',
        'bible_stories' => 'Histoires bibliques',
        'prayer' => 'Prière',
        'faith_character' => 'Foi & caractère',
        'worship_music' => 'Louange / musique',
        'christian_life' => 'Vie chrétienne',
        'series' => 'Série',
    ];

    /**
     * MANY-TO-MANY
     * Several sessions for several medias
     */
    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'media_session')->withTimestamps()->withPivot('is_viewed');
    }

    /**
     * MANY-TO-MANY
     * Several users for several medias
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'media_user')->withTimestamps()->withPivot(['is_liked', 'status_id']);
    }

    /**
     * MANY-TO-MANY
     * Several categories for several medias
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_media');
    }

    /**
     * ONE-TO-MANY
     * One type for several medias
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * ONE-TO-MANY
     * One user for several medias
     */
    public function user_owner()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * MANY-TO-ONE
     * Several orders for a media
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
