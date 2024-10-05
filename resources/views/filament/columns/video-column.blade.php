<div>
    @if ($getVideoUrl())
        <video width="320" height="240" controls>
            <source src="{{ $getVideoUrl($record) }}" type="video/mp4">
                Votre navigateur ne prend pas en charge la balise vidéo.
        </video>
    @else
        <span>Aucune vidéo disponible</span>
    @endif
</div>

