<!-- ✅ ZONE UPLOAD + PREVIEW STYLÉE -->
<div>
    <label for="video-upload">Uploader une vidéo (.mp4, .mov) :</label>
    <input type="file" id="video-upload" accept="video/mp4,video/quicktime" class="form-control" />

    <!-- 🎟️ Barre de progression chunks -->
    <div id="progress-container" style="margin-top: 10px; border: 1px solid #ccc;">
        <div id="progress-bar" style="height: 20px; background: green; width: 0%; color: #fff; text-align: center;">0%
        </div>
    </div>

    <!-- ✅ Rendu final (preview) -->
    <div id="video-wrapper" style="margin-top: 10px; display: none;">
        <video id="video-preview" width="100%" controls style="display: none;"></video>

        <div class="d-flex gap-2 mt-2">
            <button id="remove-video" type="button" class="btn btn-danger">❌ Supprimer</button>
            <a id="open-video-link" href="#" target="_blank" class="btn btn-outline-primary"
                style="display: none;">🔗 Ouvrir</a>
        </div>
        <!-- ✅ Message "prête à lire" -->
        <div id="ready-indicator" style="display: none; margin-top: 10px; color: green; font-weight: bold;">
            ✅ Vidéo prête à être visionnée.
        </div>
    </div>

    <!-- ⏳ Animation de reconstruction -->
    <div id="video-finalizing" style="display:none; text-align:center; margin-top:20px;">
        <svg xmlns="http://www.w3.org/2000/svg" style="margin:auto; background:none;" width="60" height="60"
            viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" fill="none" stroke="#3b82f6" stroke-width="10" r="35"
                stroke-dasharray="164.93361431346415 56.97787143782138">
                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s"
                    values="0 50 50;360 50 50" keyTimes="0;1" />
            </circle>
        </svg>
        <p style="margin-top: 10px;">🛠️ Reconstruction de la vidéo...<br><small>Merci de patienter.</small></p>
        <div id="rebuild-bar" style="width: 100%; height: 30px; background: #e5e7eb; margin-top: 10px;">
            <div id="rebuild-bar-fill" style="width: 0%; height: 100%; background: #3b82f6; transition: width 0.3s;">
            </div>
        </div>
        <p id="estimated-time" style="text-align: center; font-style: italic; margin-top: 8px;"></p>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- <script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('video-upload');
    const progressBar = document.getElementById('progress-bar');
    const mediaUrlField = document.querySelector('[id^="media_url_filament"]');
    const videoPreview = document.getElementById('video-preview');
    const videoWrapper = document.getElementById('video-wrapper');
    const removeBtn = document.getElementById('remove-video');
    const openBtn = document.getElementById('open-video-link');
    const finalizingBox = document.getElementById('video-finalizing');
    const readyIndicator = document.getElementById('ready-indicator');
    const chunkSize = 5 * 1024 * 1024;

    // 🔄 Affiche une vidéo (mp4 ou YouTube)
    const displayVideoPreview = (url) => {
        const oldIframe = videoPreview.parentNode.querySelector("iframe");
        if (oldIframe) oldIframe.remove();

        videoPreview.style.display = 'none';
        videoPreview.pause();
        videoPreview.removeAttribute('src');
        videoPreview.load();
        readyIndicator.style.display = 'none';

        if (url.includes('youtube.com') || url.includes('youtu.be')) {
            const videoId = url.includes('youtu.be')
                ? url.split('/').pop()
                : new URL(url).searchParams.get('v');

            videoPreview.insertAdjacentHTML('afterend', `
                <iframe width="100%" height="315" src="https://www.youtube.com/embed/${videoId}"
                frameborder="0" allowfullscreen></iframe>
            `);
        } else {
            videoPreview.setAttribute('src', url);
            videoPreview.load();
            videoPreview.style.display = 'block';
            readyIndicator.style.display = 'block';
        }

        videoWrapper.style.display = 'block';
        openBtn.href = url;
        openBtn.style.display = 'inline-block';
    };

    const clearPreview = () => {
        videoPreview.src = '';
        videoPreview.style.display = 'none';
        openBtn.style.display = 'none';
        videoWrapper.style.display = 'none';
        readyIndicator.style.display = 'none';

        const iframe = videoPreview.parentNode.querySelector("iframe");
        if (iframe) iframe.remove();
    };

    removeBtn.onclick = () => {
        if (confirm("Supprimer cette vidéo ?")) {
            clearPreview();
            mediaUrlField.value = '';
            mediaUrlField.dispatchEvent(new Event('input', { bubbles: true }));
        }
    };

    const initialMediaUrl = mediaUrlField?.value;
    if (initialMediaUrl && initialMediaUrl.startsWith('http')) {
        displayVideoPreview(initialMediaUrl);
    }

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
        const rebuildProgress = document.getElementById('rebuild-bar-fill');
        let progress = 0;
        rebuildProgress.style.width = '0%';
        const rebuildInterval = setInterval(() => {
            if (progress < 95) {
                progress += 1 + Math.random() * 2;
                rebuildProgress.style.width = progress + '%';
            }
        }, 150);

        const finalizeData = new FormData();
        finalizeData.append('uploadId', uploadId);
        finalizeData.append('filename', file.name);
        finalizeData.append('total', totalChunks);

        const finalizeResponse = await fetch("{{ route('video.chunk.finalize') }}", {
            method: "POST",
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            body: finalizeData,
        });

        const rawText = await finalizeResponse.text();
        finalizingBox.style.display = 'none';
        clearInterval(rebuildInterval);

        try {
            const result = JSON.parse(rawText);
            if (result.path) {
                mediaUrlField.value = result.path;
                mediaUrlField.dispatchEvent(new Event('input', { bubbles: true }));
                displayVideoPreview(result.path);

                Swal.fire({
                    title: '🎉 Vidéo prête',
                    text: 'Vous pouvez maintenant la visionner.',
                    icon: 'success',
                    timer: 2500,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            } else {
                alert('❌ Une erreur est survenue.');
            }
        } catch (e) {
            console.error("Erreur JSON : ", rawText);
            alert('❌ Erreur de traitement serveur.');
        }
    });
});
</script> --}}

{{-- ✅ Script de gestion de l'upload vidéo avec reconstitution simulée et suivi visuel dynamique --}}

{{-- // ✅ Script de gestion de l'upload vidéo avec reconstitution simulée et récupération dynamique du lien réel --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('video-upload');
        const progressBar = document.getElementById('progress-bar');
        const mediaUrlField = document.querySelector('[id^="media_url_filament"]');
        const videoPreview = document.getElementById('video-preview');
        const videoWrapper = document.getElementById('video-wrapper');
        const removeBtn = document.getElementById('remove-video');
        const openBtn = document.getElementById('open-video-link');
        const finalizingBox = document.getElementById('video-finalizing');
        const estimatedText = document.getElementById('estimated-time');
        const rebuildProgress = document.getElementById('rebuild-bar-fill');
        const chunkSize = 5 * 1024 * 1024;

        const displayVideoPreview = (url) => {
            const oldIframe = videoPreview.parentNode.querySelector("iframe");
            if (oldIframe) oldIframe.remove();

            videoPreview.style.display = 'none';
            videoPreview.pause();
            videoPreview.removeAttribute('src');
            videoPreview.load();

            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                const videoId = url.includes('youtu.be') ?
                    url.split('/').pop() :
                    new URL(url).searchParams.get('v');

                videoPreview.insertAdjacentHTML('afterend', `
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/${videoId}"
                    frameborder="0" allowfullscreen></iframe>
                `);
            } else {
                videoPreview.setAttribute('src', url);
                videoPreview.load();
                videoPreview.style.display = 'block';
            }

            videoWrapper.style.display = 'block';
            openBtn.href = url;
            openBtn.style.display = 'inline-block';
        };

        const clearPreview = () => {
            videoPreview.src = '';
            videoPreview.style.display = 'none';
            openBtn.style.display = 'none';
            videoWrapper.style.display = 'none';
            const iframe = videoPreview.parentNode.querySelector("iframe");
            if (iframe) iframe.remove();
        };

        removeBtn.onclick = () => {
            if (confirm("Supprimer cette vidéo ?")) {
                clearPreview();
                mediaUrlField.value = '';
                mediaUrlField.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            }
        };

        const initialMediaUrl = mediaUrlField?.value;
        if (initialMediaUrl && initialMediaUrl.startsWith('http')) {
            displayVideoPreview(initialMediaUrl);
        }

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

            // 🔄 Simulation de la reconstitution
            finalizingBox.style.display = 'block';
            rebuildProgress.style.width = '0%';
            rebuildProgress.textContent = '0%';
            rebuildProgress.style.transition = 'width 0.4s ease';
            rebuildProgress.style.textAlign = 'center';
            rebuildProgress.style.fontWeight = 'bold';

            let percent = 0;
            const simulationInterval = setInterval(async () => {
                percent += Math.random() * 4 + 1;
                if (percent >= 100) {
                    percent = 100;
                    clearInterval(simulationInterval);
                    rebuildProgress.style.width = '100%';
                    rebuildProgress.textContent = '100%';
                    estimatedText.innerText = '';

                    // 🟢 On appelle le serveur pour obtenir le vrai lien après simulation
                    const finalizeData = new FormData();
                    finalizeData.append('uploadId', uploadId);
                    finalizeData.append('filename', file.name);
                    finalizeData.append('total', totalChunks);

                    const finalizeResponse = await fetch(
                        "{{ route('video.chunk.finalize') }}", {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: finalizeData,
                        });

                    const rawText = await finalizeResponse.text();
                    try {
                        const result = JSON.parse(rawText);
                        if (result.path) {
                            mediaUrlField.value = result.path;
                            mediaUrlField.dispatchEvent(new Event('input', {
                                bubbles: true
                            }));
                            displayVideoPreview(result.path);

                            Swal.fire({
                                title: '🎉 Vidéo prête',
                                text: 'Lien récupéré et champ mis à jour !',
                                icon: 'success',
                                timer: 2500,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false
                            });
                        } else {
                            throw new Error('Aucun lien reçu');
                        }
                    } catch (e) {
                        console.error('Erreur JSON :', rawText);
                        Swal.fire('Erreur', 'Échec de la récupération du lien.',
                            'error');
                    }

                    finalizingBox.style.display = 'none';
                }

                rebuildProgress.style.width = percent + '%';
                rebuildProgress.textContent = Math.round(percent) + '%';
                estimatedText.innerText =
                    `⏳ Simulation : ${Math.ceil(100 - percent)}% restantes...`;

                if (percent < 40) {
                    rebuildProgress.style.backgroundColor = '#dc3545';
                    rebuildProgress.style.color = 'white';
                } else if (percent < 80) {
                    rebuildProgress.style.backgroundColor = '#ffc107';
                    rebuildProgress.style.color = '#000';
                } else {
                    rebuildProgress.style.backgroundColor = '#28a745';
                    rebuildProgress.style.color = 'white';
                }
            }, 250);
        });
    });
</script>


