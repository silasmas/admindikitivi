<style>
    /* Boutons de relance bien visibles dans la modale d’erreur de finalisation vidéo */
    .upload-finalize-error-swal .swal2-actions .swal2-confirm,
    .upload-finalize-error-swal .swal2-actions .swal2-deny {
        min-height: 44px; padding: 0.6rem 1.25rem; font-size: 1rem; font-weight: 700;
    }
    .upload-finalize-error-swal .swal2-actions .swal2-deny { background: #059669 !important; }
</style>
<!-- ✅ ZONE UPLOAD + PREVIEW AMÉLIORÉE -->
<div class="video-upload-wrapper" style="padding: 1.5rem; border-radius: 0.75rem; background: linear-gradient(to bottom, #f9fafb, #ffffff); border: 2px dashed #d1d5db;">
    <label for="video-upload" style="display: block; font-weight: 600; font-size: 1rem; color: #374151; margin-bottom: 0.75rem;">
        📹 Uploader une vidéo (.mp4, .mov)
    </label>
    <input type="file" id="video-upload" accept="video/mp4,video/quicktime" 
           style="display: block; width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background: white; cursor: pointer;" />

    <!-- 🎟️ Barre de progression upload (chunks) + taille uploadée -->
    <div id="progress-container" style="margin-top: 1rem; border-radius: 0.5rem; overflow: hidden; background: #e5e7eb; height: 32px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
        <div id="progress-bar" style="height: 100%; background: linear-gradient(90deg, #10b981, #059669); width: 0%; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; transition: width 0.3s ease;">
            0%
        </div>
    </div>
    <p id="progress-size" style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280; display: none;"></p>

    <!-- ✅ Preview vidéo améliorée -->
    <div id="video-wrapper" style="margin-top: 1.5rem; display: none; padding: 1rem; border-radius: 0.75rem; background: #f9fafb; border: 1px solid #e5e7eb;">
        <p style="font-weight: 600; margin-bottom: 0.75rem; color: #374151;">📺 Prévisualisation :</p>
        <video id="video-preview" width="100%" controls style="display: none; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></video>

        <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
            <button id="remove-video" type="button" style="padding: 0.5rem 1rem; border-radius: 0.5rem; background: #ef4444; color: white; border: none; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                ❌ Supprimer
            </button>
            <a id="open-video-link" href="#" target="_blank" rel="noopener" 
               style="display: none; padding: 0.5rem 1rem; border-radius: 0.5rem; background: #3b82f6; color: white; text-decoration: none; font-weight: 600; transition: background 0.2s;">
                🔗 Ouvrir dans un nouvel onglet
            </a>
        </div>
        <div id="ready-indicator" style="display: none; margin-top: 1rem; padding: 0.75rem; background: #d1fae5; color: #065f46; border-radius: 0.5rem; font-weight: 600;">
            ✅ Vidéo prête à être visionnée et enregistrée.
        </div>
    </div>

    <!-- ⏳ Traitement en cours -->
    <div id="video-finalizing" style="display:none; text-align:center; margin-top: 1.5rem; padding: 1.5rem; border-radius: 0.75rem; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <svg xmlns="http://www.w3.org/2000/svg" style="margin:auto; background:none;" width="60" height="60"
            viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="50" cy="50" fill="none" stroke="#3b82f6" stroke-width="8" r="35"
                stroke-dasharray="164.93361431346415 56.97787143782138">
                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s"
                    values="0 50 50;360 50 50" keyTimes="0;1" />
            </circle>
        </svg>
        <p style="margin-top: 1rem; font-size: 1.125rem; font-weight: 600; color: #1e40af;">
            🎬 Traitement de la vidéo en cours...
        </p>
        <div id="rebuild-bar" style="width: 100%; height: 36px; background: #e5e7eb; margin-top: 1rem; border-radius: 0.5rem; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
            <div id="rebuild-bar-fill" style="width: 0%; height: 100%; background: linear-gradient(90deg, #3b82f6, #2563eb); transition: width 0.4s ease; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
            </div>
        </div>
        <p id="estimated-time" style="text-align: center; font-style: italic; margin-top: 0.75rem; color: #6b7280; font-size: 0.875rem;"></p>
    </div>

    <!-- 🔄 Zone de relance bien visible (affichée après échec) -->
    <div id="retry-assembly-box" style="display: none; margin-top: 1.25rem; padding: 1.25rem; border-radius: 0.75rem; background: linear-gradient(135deg, #fef9c3 0%, #fde68a 100%); border: 2px solid #ca8a04; box-shadow: 0 4px 12px rgba(202,138,4,0.25);">
        <p style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 700; color: #854d0e;">⚠️ L’assemblage ou l’envoi vers S3 a échoué</p>
        <p style="margin: 0 0 1rem 0; font-size: 0.9rem; color: #a16207;">Les morceaux sont prêts sur le serveur. Cliquez ci-dessous pour réessayer sans reposter la vidéo.</p>
        <button id="retry-assembly-btn" type="button" style="padding: 0.75rem 1.5rem; font-size: 1rem; border-radius: 0.5rem; background: #059669; color: white; border: none; font-weight: 700; cursor: pointer; box-shadow: 0 2px 6px rgba(5,150,105,0.4);">
            🔄 Relancer l’assemblage
        </button>
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
            progressBar.textContent = `${percent}% (${i + 1}/${totalChunks} morceaux)`;
            progressBar.style.background = percent < 50 
                ? 'linear-gradient(90deg, #ef4444, #dc2626)' 
                : percent < 80 
                ? 'linear-gradient(90deg, #f59e0b, #d97706)' 
                : 'linear-gradient(90deg, #10b981, #059669)';
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
        const progressSizeEl = document.getElementById('progress-size');
        const mediaUrlField = document.querySelector('[id^="media_url_filament"]');
        const videoPreview = document.getElementById('video-preview');
        const videoWrapper = document.getElementById('video-wrapper');
        const removeBtn = document.getElementById('remove-video');
        const openBtn = document.getElementById('open-video-link');
        const finalizingBox = document.getElementById('video-finalizing');
        const estimatedText = document.getElementById('estimated-time');
        const rebuildProgress = document.getElementById('rebuild-bar-fill');
        const retryAssemblyBox = document.getElementById('retry-assembly-box');
        const retryAssemblyBtn = document.getElementById('retry-assembly-btn');
        if (retryAssemblyBtn) {
            retryAssemblyBtn.addEventListener('click', function () {
                if (typeof window.__retryFinalize === 'function') window.__retryFinalize();
            });
        }
        const chunkSize = 5 * 1024 * 1024;
        const storageBaseUrl = @json(asset('storage'));

        const displayVideoPreview = (url) => {
            const oldIframe = videoPreview.parentNode.querySelector("iframe");
            if (oldIframe) oldIframe.remove();

            videoPreview.style.display = 'none';
            videoPreview.pause();
            videoPreview.removeAttribute('src');
            videoPreview.load();

            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                let videoId = null;
                try {
                    videoId = url.includes('youtu.be') ?
                        url.split('/').pop()?.split('?')[0] :
                        (new URL(url).searchParams.get('v') || null);
                } catch (_) {}
                const prevInvalid = videoPreview.parentNode.querySelector('p.video-invalid-msg');
                if (prevInvalid) prevInvalid.remove();
                if (videoId && videoId !== 'null' && videoId !== 'undefined') {
                    videoPreview.insertAdjacentHTML('afterend', `
                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/${videoId}"
                        frameborder="0" allowfullscreen></iframe>
                    `);
                } else {
                    videoPreview.insertAdjacentHTML('afterend', '<p class="video-invalid-msg text-amber-600 text-sm mt-2">Ce lien ressemble à un lien YouTube mais l’identifiant vidéo est manquant ou invalide. Vérifiez l’URL ou utilisez un lien de vidéo direct (mp4).</p>');
                }
        } else {
            videoPreview.setAttribute('src', url);
            videoPreview.load();
            videoPreview.style.display = 'block';
            document.getElementById('ready-indicator').style.display = 'block';
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
            setMediaUrlStorage('');
            const ri = document.getElementById('ready-indicator');
            if (ri) ri.style.display = 'none';
            const iframe = videoPreview.parentNode.querySelector("iframe");
            if (iframe) iframe.remove();
            const invalidMsg = videoPreview.parentNode.querySelector('p.video-invalid-msg');
            if (invalidMsg) invalidMsg.remove();
        };

        removeBtn.onclick = async () => {
            if (!confirm("Supprimer cette vidéo ? Elle sera supprimée du dépôt AWS et de la base de données.")) return;
            const currentMediaUrl = getMediaUrlValue();
            if (currentMediaUrl) {
                removeBtn.disabled = true;
                removeBtn.textContent = '⏳ Suppression...';
                try {
                    const fd = new FormData();
                    fd.append('media_url', currentMediaUrl);
                    fd.append('_token', '{{ csrf_token() }}');
                    const resp = await fetch("{{ route('video.chunk.delete') }}", {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await resp.json().catch(() => ({}));
                    if (!resp.ok) {
                        throw new Error(data.message || data.error || 'Erreur lors de la suppression');
                    }
                } catch (e) {
                    removeBtn.disabled = false;
                    removeBtn.textContent = '❌ Supprimer';
                    alert('Erreur : ' + (e.message || 'impossible de supprimer la vidéo.'));
                    return;
                }
                removeBtn.textContent = '❌ Supprimer';
                removeBtn.disabled = false;
            }
            clearPreview();
            setMediaUrlStorage('');
            const inputField = document.querySelector('[id^="media_url_filament"]') || document.querySelector('input[name*="media_url"]');
            if (inputField) {
                inputField.value = '';
                inputField.dispatchEvent(new Event('input', { bubbles: true }));
                inputField.dispatchEvent(new Event('change', { bubbles: true }));
            }
            const form = document.querySelector('form[wire\\:submit]') || document.querySelector('form');
            if (form) {
                const wireEl = form.closest('[wire\\:id]');
                if (wireEl && typeof Livewire !== 'undefined') {
                    const id = wireEl.getAttribute('wire:id');
                    if (id && Livewire.find(id)) Livewire.find(id).set('data.media_url', null);
                }
            }
        };

        const toFullVideoUrl = (value) => {
            if (!value || typeof value !== 'string') return null;
            const v = value.trim();
            if (v.startsWith('http://') || v.startsWith('https://')) return v;
            const base = (storageBaseUrl || '').replace(/\/$/, '');
            return base ? (base + '/' + v.replace(/^\//, '')) : v;
        };

        const getMediaUrlValue = () => {
            const byId = document.querySelector('[id^="media_url_filament"]');
            if (byId && byId.value) return byId.value.trim();
            const byName = document.querySelector('input[name*="media_url"]');
            if (byName && byName.value) return byName.value.trim();
            const wrapper = document.getElementById('video-wrapper');
            if (wrapper && wrapper.dataset.mediaUrl) return wrapper.dataset.mediaUrl.trim();
            return (mediaUrlField && mediaUrlField.value) ? mediaUrlField.value.trim() : '';
        };

        const setMediaUrlStorage = (url) => {
            const w = document.getElementById('video-wrapper');
            if (w) w.dataset.mediaUrl = url || '';
        };

        const initialMediaUrl = getMediaUrlValue();
        const initialFullUrl = toFullVideoUrl(initialMediaUrl);
        if (initialFullUrl) {
            setMediaUrlStorage(initialMediaUrl);
            displayVideoPreview(initialFullUrl);
        }

        if (typeof Livewire !== 'undefined') {
            Livewire.hook('morph.updated', () => {
                const url = mediaUrlField?.value?.trim();
                const full = toFullVideoUrl(url);
                if (full && full !== initialFullUrl) displayVideoPreview(full);
            });
        }

        const formatBytes = (bytes) => (bytes / (1024 * 1024)).toFixed(2) + ' Mo';

        input.addEventListener('change', async function() {
            const file = input.files[0];
            if (!file) return;

            if (!['video/mp4', 'video/quicktime'].includes(file.type)) {
                alert('⚠️ Format non supporté.');
                return;
            }

            const uploadId = Date.now() + '-' + file.name.replace(/\s+/g, '-');
            const totalChunks = Math.ceil(file.size / chunkSize);
            const totalBytes = file.size;
            progressSizeEl.style.display = 'block';
            progressSizeEl.textContent = '0 Mo / ' + formatBytes(totalBytes);

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

                const uploadedBytes = Math.min((i + 1) * chunkSize, totalBytes);
                const percent = Math.round(((i + 1) / totalChunks) * 100);
                progressBar.style.width = percent + '%';
                progressBar.textContent = percent + '%';
                progressSizeEl.textContent = formatBytes(uploadedBytes) + ' / ' + formatBytes(totalBytes);
                progressBar.style.background =
                    percent < 40 ? '#dc3545' :
                    percent < 80 ? '#ffc107' : '#28a745';
            }

            progressSizeEl.style.display = 'none';

            const runFinalize = async (isRetry) => {
                if (retryAssemblyBox) retryAssemblyBox.style.display = 'none';
                finalizingBox.style.display = 'block';
                rebuildProgress.style.width = isRetry ? '50%' : '5%';
                rebuildProgress.textContent = (isRetry ? '50' : '5') + '%';
                estimatedText.innerText = isRetry ? '🔄 Nouvelle tentative d’envoi vers S3...' : '⏳ Assemblage et envoi vers S3...';

                const finalizeData = new FormData();
                finalizeData.append('uploadId', uploadId);
                finalizeData.append('filename', file.name);
                finalizeData.append('total', totalChunks);

                if (!isRetry) {
                    rebuildProgress.style.width = '25%';
                    rebuildProgress.textContent = '25%';
                    estimatedText.innerText = '⏳ Assemblage des morceaux...';
                }

                const finalizeResponse = await fetch("{{ route('video.chunk.finalize') }}", {
                    method: "POST",
                    headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: finalizeData,
                });

                rebuildProgress.style.width = '75%';
                rebuildProgress.textContent = '75%';
                estimatedText.innerText = '☁️ Envoi vers le serveur S3...';

                const rawText = await finalizeResponse.text();
                rebuildProgress.style.width = '100%';
                rebuildProgress.textContent = '100%';
                estimatedText.innerText = '';

                let result;
                try {
                    result = JSON.parse(rawText);
                } catch (_) {
                    finalizingBox.style.display = 'none';
                    if (retryAssemblyBox) {
                        retryAssemblyBox.style.display = 'block';
                        window.__retryFinalize = () => runFinalize(true);
                    }
                    const statusInfo = '<br><small style="color:#6b7280;">Code HTTP : ' + finalizeResponse.status + (finalizeResponse.statusText ? ' — ' + finalizeResponse.statusText : '') + '</small>';
                    const msg = finalizeResponse.ok ? 'Réponse serveur invalide (réponse non JSON).' : ('Le serveur a renvoyé une erreur.' + statusInfo + '<br><small style="word-break:break-all;margin-top:0.5rem;display:block;">' + (rawText.length > 300 ? rawText.slice(0, 300) + '…' : rawText) + '</small>');
                    Swal.fire({
                        title: 'Erreur lors de la finalisation',
                        html: '<p>' + (msg || 'Échec de la récupération du lien vidéo.') + '</p><p style="font-size:0.875rem;color:#059669;margin-top:0.75rem;">Les morceaux ont bien été envoyés. Cliquez sur <strong>« Réessayer la finalisation »</strong> ci-dessous ou sur le bouton vert <strong>« Relancer l’assemblage »</strong> sur la page.</p>',
                        icon: 'error',
                        customClass: { popup: 'upload-finalize-error-swal' },
                        showConfirmButton: true,
                        confirmButtonText: 'Fermer',
                        showDenyButton: true,
                        denyButtonText: 'Réessayer la finalisation',
                        denyButtonColor: '#059669'
                    }).then((r) => { if (r.isDenied) runFinalize(true); });
                    return;
                }

                if (result.path) {
                    if (mediaUrlField) {
                        mediaUrlField.value = result.path;
                        mediaUrlField.dispatchEvent(new Event('input', { bubbles: true }));
                        mediaUrlField.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    const form = document.querySelector('form[wire\\:submit]') || document.querySelector('form');
                    if (form) {
                        const wireEl = form.closest('[wire\\:id]');
                        if (wireEl && typeof Livewire !== 'undefined') {
                            const id = wireEl.getAttribute('wire:id');
                            if (id && Livewire.find(id)) {
                                Livewire.find(id).set('data.media_url', result.path);
                            }
                        }
                    }
                    setMediaUrlStorage(result.path);
                    displayVideoPreview(result.path);
                    finalizingBox.style.display = 'none';
                    if (retryAssemblyBox) retryAssemblyBox.style.display = 'none';
                    if (typeof window.__retryFinalize !== 'undefined') delete window.__retryFinalize;
                    Swal.fire({
                        title: '🎉 Vidéo prête',
                        html: '<p>La vidéo a été uploadée avec succès.</p><p style="font-size: 0.875rem; color: #6b7280;">Vous pouvez la prévisualiser ci-dessous avant de valider le formulaire.</p>',
                        icon: 'success',
                        timer: 3000,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false
                    });
                    return;
                }

                finalizingBox.style.display = 'none';
                if (retryAssemblyBox && result.retry_finalize) {
                    retryAssemblyBox.style.display = 'block';
                    window.__retryFinalize = () => runFinalize(true);
                }
                const errorTitle = result.error || 'Erreur lors de la finalisation';
                let errorDetail = result.details ? '<br><small style="color:#6b7280;margin-top:0.25rem;display:block;">Message : ' + result.details + '</small>' : '';
                if (result.file) {
                    errorDetail += '<br><small style="color:#dc2626;font-family:monospace;margin-top:0.25rem;display:block;"><strong>Fichier :</strong> ' + result.file + (result.line ? ' <strong>ligne ' + result.line + '</strong>' : '') + '</small>';
                }
                if (result.exception_class) {
                    errorDetail += '<br><small style="color:#6b7280;">Classe : ' + result.exception_class + '</small>';
                }
                if (result.stack_trace) {
                    errorDetail += '<br><pre style="margin-top:0.5rem;padding:0.5rem;background:#1f2937;color:#e5e7eb;font-size:0.7rem;max-height:120px;overflow:auto;text-align:left;border-radius:0.25rem;">' + result.stack_trace.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</pre>';
                }
                const statusInfo = '<br><small style="color:#6b7280;">Code HTTP : ' + (finalizeResponse ? finalizeResponse.status : '—') + '</small>';
                const canRetry = !!result.retry_finalize;
                Swal.fire({
                    title: errorTitle,
                    html: '<p>' + errorTitle + errorDetail + statusInfo + '</p><p style="font-size:0.875rem;color:#059669;margin-top:0.75rem;">Cliquez sur <strong>« Réessayer la finalisation »</strong> ci-dessous ou sur le bouton vert <strong>« Relancer l’assemblage »</strong> sur la page.</p>',
                    icon: 'error',
                    customClass: { popup: 'upload-finalize-error-swal' },
                    showConfirmButton: true,
                    confirmButtonText: 'Fermer',
                    showDenyButton: canRetry,
                    denyButtonText: 'Réessayer la finalisation',
                    denyButtonColor: '#059669'
                }).then((r) => { if (r.isDenied && canRetry) runFinalize(true); });
            };

            await runFinalize(false);
        });
    });
</script>


