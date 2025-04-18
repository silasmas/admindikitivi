@if ($mediaUrl ?? false)
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#videoModal-{{ $recordId }}">
        🎬 Voir
    </button>

    <div class="modal fade" id="videoModal-{{ $recordId }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Prévisualisation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <video width="100%" controls>
                        <source src="{{ $mediaUrl }}" type="video/mp4">
                        Votre navigateur ne supporte pas la vidéo.
                    </video>
                </div>
            </div>
        </div>
    </div>
@endif
