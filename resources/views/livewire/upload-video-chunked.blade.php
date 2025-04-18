


{{-- 
<div>
    <label for="video-upload">Uploader une vidéo (.mp4, .mov) :</label>
    <input type="file" id="video-upload" accept="video/mp4,video/quicktime" class="form-control" />

    <div id="progress-container" style="margin-top: 10px; border: 1px solid #ccc;">
        <div id="progress-bar" style="height: 20px; background: green; width: 0%; color: #fff; text-align: center;">0%</div>
    </div>

    <!-- Bloc preview vidéo -->
    <div id="video-wrapper" style="margin-top: 10px; display: none;">
        <video id="video-preview" width="100%" controls></video>
        <div class="d-flex gap-2 mt-2">
            <button id="remove-video" type="button" class="btn btn-danger">❌ Supprimer la vidéo</button>
            <a id="open-video-link" href="#" target="_blank" class="btn btn-outline-primary" style="display: none;">🔗 Ouvrir dans un nouvel onglet</a>
        </div>
    </div>

    <!-- Message reconstruction -->
    <div id="video-finalizing" style="display:none; text-align:center; margin-top:10px;">
        <span class="spinner-border text-primary" role="status"></span>
        <p>Reconstruction de la vidéo en cours...</p>
    </div>

    <!-- Ce champ DOIT correspondre à Hidden::make('media_url')->id('media_url_filament') dans Filament -->
    <input type="text" id="media_url_filament2" />
</div> --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('video-upload');
        const progressBar = document.getElementById('progress-bar');
        const mediaUrlField = document.getElementById('media_url_filament');
        const mediaUrlField2 = document.getElementById('media_url_filament2');
        const videoPreview = document.getElementById('video-preview');
        const videoWrapper = document.getElementById('video-wrapper');
        const removeBtn = document.getElementById('remove-video');
        const openBtn = document.getElementById('open-video-link');
        const finalizingBox = document.getElementById('video-finalizing');
        const chunkSize = 5 * 1024 * 1024;

        // ✅ Chargement automatique du lien existant
        const existingUrl = mediaUrlField?.value ?? '';
        const existingUrl2 = mediaUrlField2?.value ?? '';
        console.log('Existing URL:', existingUrl);
        console.log('Existing URL 2:', existingUrl2);
        if (existingUrl && existingUrl.startsWith('https://')) {
            videoPreview.src = existingUrl;
            videoWrapper.style.display = 'block';
            openBtn.href = existingUrl;
            openBtn.style.display = 'inline-block';
        }
        if (existingUrl2 && existingUrl2.startsWith('https://')) {
            videoPreview.src = existingUrl;
            videoWrapper.style.display = 'block';
            openBtn.href = existingUrl2;
            openBtn.style.display = 'inline-block';
        }

        // ❌ Bouton de suppression
        removeBtn.onclick = function () {
            if (confirm("Voulez-vous vraiment supprimer la vidéo ?")) {
                mediaUrlField.value = '';
                mediaUrlField.dispatchEvent(new Event('input', { bubbles: true }));
                mediaUrlField2.value = '';
                mediaUrlField2.dispatchEvent(new Event('input', { bubbles: true }));

                videoWrapper.style.display = 'none';
                videoPreview.src = '';
                openBtn.style.display = 'none';
            }
        };

        // 🎥 Upload logique
        input.addEventListener('change', async function () {
            const file = input.files[0];
            if (!file) return;

            if (!['video/mp4', 'video/quicktime'].includes(file.type)) {
                alert('⚠️ Format non supporté. Seuls les .mp4 et .mov sont autorisés.');
                return;
            }

            const uploadId = Date.now() + '-' + file.name.replace(/\s+/g, '-');
            const totalChunks = Math.ceil(file.size / chunkSize);

            for (let i = 0; i < totalChunks; i++) {
                const chunk = file.slice(i * chunkSize, (i + 1) * chunkSize);
                const formData = new FormData();

                formData.append('chunk', chunk);
                formData.append('index', i);
                formData.append('total', totalChunks);
                formData.append('uploadId', uploadId);
                formData.append('filename', file.name);

                await fetch("{{ route('video.chunk.upload') }}", {
                    method: "POST",
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: formData,
                });

                const percent = Math.round(((i + 1) / totalChunks) * 100);
                progressBar.style.width = percent + '%';
                progressBar.textContent = percent + '%';

                // Couleur dynamique
                if (percent < 40) {
                    progressBar.style.background = '#dc3545'; // rouge
                } else if (percent < 80) {
                    progressBar.style.background = '#ffc107'; // orange
                } else {
                    progressBar.style.background = '#28a745'; // vert
                }
            }

            finalizingBox.style.display = 'block';

            const finalizeData = new FormData();
            finalizeData.append('uploadId', uploadId);
            finalizeData.append('filename', file.name);
            finalizeData.append('total', totalChunks);

            const finalizeResponse = await fetch("{{ route('video.chunk.finalize') }}", {
                method: "POST",
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                body: finalizeData,
            });

            const result = await finalizeResponse.json();
            finalizingBox.style.display = 'none';

            if (result.path) {
                mediaUrlField.value = result.path;
                mediaUrlField.dispatchEvent(new Event('input', { bubbles: true }));
                mediaUrlField2.value = result.path;
                mediaUrlField2.dispatchEvent(new Event('input', { bubbles: true }));

                videoPreview.src = result.path;
                videoWrapper.style.display = 'block';
                openBtn.href = result.path;
                openBtn.style.display = 'inline-block';

                alert('✅ Vidéo uploadée et reconstituée avec succès.');
            } else {
                alert('❌ Une erreur est survenue.');
            }
        });

        // 🔒 Bloque le submit si aucune vidéo
        document.querySelector('form')?.addEventListener('submit', function (e) {
            if (!mediaUrlField?.value || !mediaUrlField.value.startsWith('https://')) {
                e.preventDefault();
                alert('❌ Vous devez uploader une vidéo avant d’enregistrer.');
            }
        });
    });
</script> --}}
<div>
    <label for="video-upload">Uploader une vidéo (.mp4, .mov) :</label>
    <input type="file" id="video-upload" accept="video/mp4,video/quicktime" class="form-control" />

    <div id="progress-container" style="margin-top: 10px; border: 1px solid #ccc;">
        <div id="progress-bar" style="height: 20px; background: green; width: 0%; color: #fff; text-align: center;">0%</div>
    </div>

    <div id="video-wrapper" style="margin-top: 10px; display: none;">
        <video id="video-preview" width="100%" controls style="display:none;"></video>

        <div class="d-flex gap-2 mt-2">
            <button id="remove-video" type="button" class="btn btn-danger">❌ Supprimer</button>
            <a id="open-video-link" href="#" target="_blank" class="btn btn-outline-primary" style="display: none;">🔗 Ouvrir dans un onglet</a>
            <button id="preview-button" type="button" class="btn btn-success" style="display: none;" data-bs-toggle="modal" data-bs-target="#videoModal">🎬 Prévisualiser</button>
        </div>
    </div>

    <div id="video-finalizing" style="display:none; text-align:center; margin-top:10px;">
        <span class="spinner-border text-primary" role="status"></span>
        <p>Reconstruction de la vidéo en cours...</p>
    </div>

    <!-- Filament Hidden field synchronisé -->
    <input type="hidden" id="media_url_filament" />
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Prévisualisation de la vidéo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-center">
        <video id="modal-video-player" width="100%" controls></video>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('video-upload');
        const progressBar = document.getElementById('progress-bar');
        const mediaUrlField = document.getElementById('media_url_filament');
        const videoPreview = document.getElementById('video-preview');
        const videoWrapper = document.getElementById('video-wrapper');
        const removeBtn = document.getElementById('remove-video');
        const openBtn = document.getElementById('open-video-link');
        const previewBtn = document.getElementById('preview-button');
        const modalVideo = document.getElementById('modal-video-player');
        const finalizingBox = document.getElementById('video-finalizing');
        const chunkSize = 5 * 1024 * 1024;

        const displayVideoPreview = (url) => {
            videoPreview.src = url;
            modalVideo.src = url;
            videoPreview.style.display = 'block';
            videoWrapper.style.display = 'block';
            openBtn.href = url;
            openBtn.style.display = 'inline-block';
            previewBtn.style.display = 'inline-block';
        };

        const clearPreview = () => {
            videoPreview.src = '';
            modalVideo.src = '';
            videoPreview.style.display = 'none';
            videoWrapper.style.display = 'none';
            openBtn.style.display = 'none';
            previewBtn.style.display = 'none';
        };

        // 🔁 Afficher si valeur déjà existante
        const existingUrl = mediaUrlField?.value ?? '';
        if (existingUrl && existingUrl.startsWith('http')) {
            displayVideoPreview(existingUrl);
        }

        removeBtn.onclick = function () {
            if (confirm("Voulez-vous vraiment supprimer la vidéo ?")) {
                clearPreview();
                mediaUrlField.value = '';
                mediaUrlField.dispatchEvent(new Event('input', { bubbles: true }));
            }
        };

        input.addEventListener('change', async function () {
            const file = input.files[0];
            if (!file) return;

            if (!['video/mp4', 'video/quicktime'].includes(file.type)) {
                alert('⚠️ Format non supporté.');
                return;
            }

            const uploadId = Date.now() + '-' + file.name.replace(/\s+/g, '-');
            const totalChunks = Math.ceil(file.size / chunkSize);

            for (let i = 0; i < totalChunks; i++) {
                const chunk = file.slice(i * chunkSize, (i + 1) * chunkSize);
                const formData = new FormData();

                formData.append('chunk', chunk);
                formData.append('index', i);
                formData.append('total', totalChunks);
                formData.append('uploadId', uploadId);
                formData.append('filename', file.name);

                await fetch("{{ route('video.chunk.upload') }}", {
                    method: "POST",
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: formData,
                });

                const percent = Math.round(((i + 1) / totalChunks) * 100);
                progressBar.style.width = percent + '%';
                progressBar.textContent = percent + '%';
                progressBar.style.background =
                    percent < 40 ? '#dc3545' :
                    percent < 80 ? '#ffc107' : '#28a745';
            }

            finalizingBox.style.display = 'block';

            const finalizeData = new FormData();
            finalizeData.append('uploadId', uploadId);
            finalizeData.append('filename', file.name);
            finalizeData.append('total', totalChunks);

            const finalizeResponse = await fetch("{{ route('video.chunk.finalize') }}", {
                method: "POST",
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                body: finalizeData,
            });

            const result = await finalizeResponse.json();
            finalizingBox.style.display = 'none';

            if (result.path) {
                mediaUrlField.value = result.path;
                mediaUrlField.dispatchEvent(new Event('input', { bubbles: true }));
                displayVideoPreview(result.path);
                alert('✅ Vidéo uploadée et reconstituée avec succès.');
            } else {
                alert('❌ Une erreur est survenue.');
            }
        });

        // 🔒 Bloquer submit si media_url vide
        document.querySelector('form')?.addEventListener('submit', function (e) {
            if (!mediaUrlField?.value || !mediaUrlField.value.startsWith('http')) {
                e.preventDefault();
                alert('❌ Veuillez d’abord uploader une vidéo.');
            }
        });
    });
</script>

