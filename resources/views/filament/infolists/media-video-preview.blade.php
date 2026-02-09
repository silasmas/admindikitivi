@php
    $record = $getRecord();
    $rawMediaUrl = $record->media_url ?? null;
    $source = $record->source ?? null;
    $resolved = resolve_media_video($rawMediaUrl, $source);
    $mediaUrl = $resolved['url'];
    $videoType = $resolved['type'];
    $thumbnail = $record->thumbnail_url ?? asset('assets/images/logo.png');
    $logoFallback = asset('assets/images/logo.png');
@endphp

@push('scripts')
<script>
if(typeof window.openMediaVideo!=='function'){
    window.openMediaVideo=function(type,u){
        type=(type||'').toLowerCase();var v;
        if(type==='youtube'||u.indexOf('youtube')>=0||u.indexOf('youtu.be')>=0){
            var m=u.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&?]+)/);
            v='<iframe width="100%" height="400" src="https://www.youtube.com/embed/'+(m?m[1]:'')+'?autoplay=1" frameborder="0" allowfullscreen></iframe>';
        }else if(type==='vimeo'||u.indexOf('vimeo')>=0){
            var m2=u.match(/vimeo\.com\/(\d+)/);
            v=m2?'<iframe width="100%" height="400" src="https://player.vimeo.com/video/'+m2[1]+'?autoplay=1" frameborder="0" allowfullscreen></iframe>':'<video width="100%" height="400" controls autoplay><source src="'+u+'" type="video/mp4"></video>';
        }else{
            v='<video width="100%" height="400" controls autoplay><source src="'+u+'" type="video/mp4"></video>';
        }
        var d=document.createElement('div');
        d.id='filament-video-modal';
        d.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,.85);display:flex;justify-content:center;align-items:center;z-index:99999';
        d.innerHTML='<div style="background:var(--fi-body-bg,#fff);padding:1.5rem;border-radius:8px;max-width:90vw;max-height:90vh;overflow:auto"><div>'+v+'</div><div style="margin-top:1rem;text-align:right"><button type="button" onclick="document.getElementById(\'filament-video-modal\').remove()" style="padding:.5rem 1rem;background:#dc2626;color:white;border-radius:4px;cursor:pointer">Fermer</button></div></div>';
        document.body.appendChild(d);
    };
}
</script>
@endpush

<div>
@if ($mediaUrl)
    <div class="space-y-3">
        <div class="flex items-center gap-4 flex-wrap">
            <div class="relative rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800" style="width: 200px; height: 112px;">
                @if ($videoType === 'youtube')
                    @php
                        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&?]+)/', $mediaUrl, $m);
                        $ytId = $m[1] ?? null;
                        $thumbUrl = $ytId ? "https://img.youtube.com/vi/{$ytId}/mqdefault.jpg" : $thumbnail;
                    @endphp
                    <img src="{{ $thumbUrl }}" alt="Aperçu" class="w-full h-full object-cover" onerror="this.src='{{ $logoFallback }}'">
                @elseif ($videoType === 'vimeo')
                    @php
                        preg_match('/vimeo\.com\/(\d+)/', $mediaUrl, $m);
                        $vimeoId = $m[1] ?? null;
                    @endphp
                    @if ($vimeoId)
                        <img src="https://vumbnail.com/{{ $vimeoId }}.jpg" alt="Aperçu" class="w-full h-full object-cover" onerror="this.src='{{ $logoFallback }}'">
                    @else
                        <img src="{{ $thumbnail }}" alt="Aperçu" class="w-full h-full object-cover" onerror="this.src='{{ $logoFallback }}'">
                    @endif
                @else
                    <img src="{{ $thumbnail }}" alt="Aperçu" class="w-full h-full object-cover" onerror="this.src='{{ $logoFallback }}'">
                @endif
            </div>
            <div class="flex flex-col gap-2">
                <a href="{{ $mediaUrl }}" target="_blank" rel="noopener" class="text-primary-600 dark:text-primary-400 hover:underline text-sm">
                    ↗ Lien vers la vidéo
                </a>
                <button type="button"
                    onclick="window.openMediaVideo('{{ $videoType }}', '{{ addslashes($mediaUrl) }}')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-500 text-white text-sm font-medium rounded-lg">
                    ▶ Lire
                </button>
            </div>
        </div>
    </div>
@else
    <p class="text-gray-500 dark:text-gray-400 text-sm">Aucune vidéo disponible.</p>
@endif
</div>
