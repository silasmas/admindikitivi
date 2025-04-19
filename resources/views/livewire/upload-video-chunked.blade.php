<div>
    <label for="video-upload">Uploader une vidéo (.mp4, .mov) :</label>
    <input type="file" id="video-upload" accept="video/mp4,video/quicktime" class="form-control" />

    <!-- Barre de progression -->
    <div id="progress-container" style="margin-top: 10px; border: 1px solid #ccc;">
        <div id="progress-bar" style="height: 20px; background: green; width: 0%; color: #fff; text-align: center;">0%
        </div>
    </div>

    <!-- Prévisualisation -->
    <div id="video-wrapper" style="margin-top: 10px; display: none;">
        <video id="video-preview" width="100%" controls style="display: none;"></video>

        <div class="d-flex gap-2 mt-2">
            <button id="remove-video" type="button" class="btn btn-danger">❌ Supprimer</button>
            <a id="open-video-link" href="#" target="_blank" class="btn btn-outline-primary"
                style="display: none;">🔗 Ouvrir</a>
            <button id="preview-button" type="button" class="btn btn-success" style="display: none;"
                data-bs-toggle="modal" data-bs-target="#videoModal">🎬 Prévisualiser</button>
        </div>
    </div>

    <!-- Message reconstruction -->
    <div id="video-finalizing" style="display:none; text-align:center; margin-top:10px;">
        <span class="spinner-border text-primary" role="status"></span>
        <p>Reconstruction de la vidéo en cours...</p>
    </div>

    <!-- Champ hidden lié à Filament -->
    {{-- <input type="text" id="media_url_filament" /> --}}
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prévisualisation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-center">
                <video id="modal-video-player" width="100%" controls></video>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('video-upload');
        const progressBar = document.getElementById('progress-bar');
        const mediaUrlField = document.querySelector('[id^="media_url_filament"]');

        const videoPreview = document.getElementById('video-preview');
        const videoWrapper = document.getElementById('video-wrapper');
        const removeBtn = document.getElementById('remove-video');
        const openBtn = document.getElementById('open-video-link');
        const previewBtn = document.getElementById('preview-button');
        const modalVideo = document.getElementById('modal-video-player');
        const finalizingBox = document.getElementById('video-finalizing');
        const chunkSize = 5 * 1024 * 1024;

        const displayVideoPreview = (url) => {
            videoWrapper.style.display = 'block';
            openBtn.style.display = 'inline-block';
            previewBtn.style.display = 'inline-block';
            openBtn.href = url;
            modalVideo.src = url;

            const oldIframe = videoPreview.parentNode.querySelector("iframe");
            if (oldIframe) oldIframe.remove();

            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                const videoId = url.includes('youtu.be') ? url.split('/').pop() : new URL(url).searchParams
                    .get('v');
                videoPreview.style.display = 'none';
                videoPreview.src = '';
                videoPreview.insertAdjacentHTML('afterend', `
            <iframe width="100%" height="315" src="https://www.youtube.com/embed/${videoId}"
            frameborder="0" allowfullscreen></iframe>
        `);
            } else {
                videoPreview.src = url;
                videoPreview.style.display = 'block';
            }
        };


        const clearPreview = () => {
            videoPreview.src = '';
            modalVideo.src = '';
            videoPreview.style.display = 'none';
            videoWrapper.style.display = 'none';
            openBtn.style.display = 'none';
            previewBtn.style.display = 'none';

            const iframe = videoPreview.parentNode.querySelector("iframe");
            if (iframe) iframe.remove();
        };


        // 🔁 Afficher lien si déjà existant
        const existingUrl = mediaUrlField?.value ?? '';
        // if (existingUrl && existingUrl.startsWith('http')) {
        //     displayVideoPreview(existingUrl);
        // }

        removeBtn.onclick = () => {
            if (confirm("Supprimer cette vidéo ?")) {
                clearPreview();
                mediaUrlField.value = '';
                mediaUrlField.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            }
        };


        // input.addEventListener('change', async function() {
        //     const file = input.files[0];
        //     if (!file) return;

        //     if (!['video/mp4', 'video/quicktime'].includes(file.type)) {
        //         alert('⚠️ Format non supporté.');
        //         return;
        //     }

        //     const uploadId = Date.now() + '-' + file.name.replace(/\s+/g, '-');
        //     const totalChunks = Math.ceil(file.size / chunkSize);

        //     for (let i = 0; i < totalChunks; i++) {
        //         const chunk = file.slice(i * chunkSize, (i + 1) * chunkSize);
        //         const formData = new FormData();
        //         formData.append('chunk', chunk);
        //         formData.append('index', i);
        //         formData.append('total', totalChunks);
        //         formData.append('uploadId', uploadId);
        //         formData.append('filename', file.name);

        //         await fetch("{{ route('video.chunk.upload') }}", {
        //             method: "POST",
        //             headers: {
        //                 'X-CSRF-TOKEN': "{{ csrf_token() }}"
        //             },
        //             body: formData,
        //         });

        //         const percent = Math.round(((i + 1) / totalChunks) * 100);
        //         progressBar.style.width = percent + '%';
        //         progressBar.textContent = percent + '%';
        //         progressBar.style.background =
        //             percent < 40 ? '#dc3545' :
        //             percent < 80 ? '#ffc107' : '#28a745';
        //     }

        //     finalizingBox.style.display = 'block';

        //     const finalizeData = new FormData();
        //     finalizeData.append('uploadId', uploadId);
        //     finalizeData.append('filename', file.name);
        //     finalizeData.append('total', totalChunks);

        //     const finalizeResponse = await fetch("{{ route('video.chunk.finalize') }}", {
        //         method: "POST",
        //         headers: {
        //             'X-CSRF-TOKEN': "{{ csrf_token() }}"
        //         },
        //         body: finalizeData,
        //     });

        //     const rawText = await finalizeResponse.text();
        //     let result;

        //     finalizingBox.style.display = 'none';

        //     try {
        //         result = JSON.parse(rawText);

        //         if (result.path) {
        //             console.log('✅ Lien à injecter dans media_url_filament:', result.path);
        //             const mediaUrlField = document.querySelector('[id^="media_url_filament"]');

        //             if (mediaUrlField) {
        //                 mediaUrlField.value = result.path;
        //                 mediaUrlField.dispatchEvent(new Event('input', {
        //                     bubbles: true
        //                 }));
        //             }

        //             console.log('🧪 Nouveau contenu du champ :', mediaUrlField.value);
        //             displayVideoPreview(result.path);

        //             Swal.fire({
        //                 title: '🎉 Vidéo enregistrée',
        //                 text: 'Le lien a été mis à jour dans le formulaire.' + result.path,
        //                 icon: 'success',
        //                 confirmButtonText: 'OK'
        //             });
        //         } else {
        //             alert('❌ Une erreur est survenue.');
        //         }
        //     } catch (e) {
        //         console.error("Erreur JSON : ", rawText);
        //         alert('❌ Erreur de traitement serveur. Voir la console.');
        //     }
        // });
        input.addEventListener('change', async function() {
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
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
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
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: finalizeData,
            });

            const rawText = await finalizeResponse.text();
            finalizingBox.style.display = 'none';

            try {
                const result = JSON.parse(rawText);

                if (result.path) {
                    const mediaUrlField = document.querySelector('[id^="media_url_filament"]');

                    if (mediaUrlField) {
                        mediaUrlField.value = result.path;
                        mediaUrlField.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                    }

                    Swal.fire({
                        title: '🎉 Vidéo enregistrée',
                        text: 'Lien mis à jour dans le formulaire.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });

                    // Preview automatique si tu veux (sinon commente)
                    // displayVideoPreview(result.path);
                } else {
                    alert('❌ Une erreur est survenue.');
                }
            } catch (e) {
                console.error("Erreur JSON : ", rawText);
                alert('❌ Erreur de traitement serveur.');
            }
        });

        document.querySelector('form')?.addEventListener('submit', function(e) {
            if (!mediaUrlField?.value || !mediaUrlField.value.startsWith('http')) {
                e.preventDefault();
                alert('❌ Veuillez uploader une vidéo avant d’enregistrer.');
            }
        });
        // 🔁 Permet la prévisualisation manuelle via bouton 📝
        window.addEventListener('preview-media-url', () => {
            const mediaUrlField = document.querySelector('[id^="media_url_filament"]');
            const url = mediaUrlField?.value;

            if (url && url.startsWith('http')) {
                displayVideoPreview(url);
                Swal.fire({
                    icon: 'info',
                    title: '🔁 Prévisualisation à jour',
                    text: 'La vidéo affichée correspond au lien.',
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lien manquant',
                    text: 'Aucun lien de vidéo à afficher.',
                });
            }
        });

        window.addEventListener('preview-media-url', () => {
            const mediaUrlField = document.querySelector('[id^="media_url_filament"]');
            const url = mediaUrlField?.value;

            if (url && url.startsWith('http')) {
                displayVideoPreview(url); // ta fonction déjà en place
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lien non valide',
                    text: 'Aucun lien de vidéo valide à afficher.',
                });
            }
        });


    });
</script>
