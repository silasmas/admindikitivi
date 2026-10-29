<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Log de lecture / performance envoyé par les applications (table créée par la migration de l'API).
 */
class PlaybackEvent extends Model
{
    use HasFactory;

    protected $table = 'playback_events';

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'props' => 'array',
        'occurred_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Libellés des événements affichés dans le back-office.
     */
    public const EVENT_LABELS = [
        'video_play' => 'Lecture',
        'video_complete' => 'Fin de vidéo',
        'video_abandon' => 'Abandon',
        'playback_error' => 'Erreur de lecture',
        'buffer_start' => 'Buffering (début)',
        'buffer_end' => 'Buffering (fin)',
        'video_startup_ms' => 'Démarrage vidéo (ms)',
        'thumbnail_load_ms' => 'Chargement vignette (ms)',
        'api_error' => 'Erreur API',
        'recommendation_click' => 'Clic recommandation',
        'autoplay_next' => 'Autoplay lancé',
        'autoplay_cancel' => 'Autoplay annulé',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
