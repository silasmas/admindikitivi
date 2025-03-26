<x-filament::page>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($records as $media)
            <div class="bg-white shadow p-4 rounded">
                <img src="{{ $media->thumbnail_url }}" alt="Miniature" class="w-full h-48 object-cover rounded mb-2">
                <div class="text-lg font-bold truncate">{{ $media->media_title }}</div>
                <div class="text-sm text-gray-500 mb-2">{{ ucfirst($media->source) }}</div>
                <button
                    onclick="playVideo('{{ $media->source }}', '{{ $media->media_url }}')"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                >
                    ▶️ Lire
                </button>
            </div>
        @endforeach
    </div>

    <!-- Modal Player -->
    <div id="video-modal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50">
        <div class="bg-white rounded-lg p-4 max-w-2xl w-full">
            <div id="video-container" class="aspect-video"></div>
            <button onclick="closeModal()" class="mt-4 bg-red-600 text-white px-4 py-2 rounded">Fermer</button>
        </div>
    </div>

    <script>
        function playVideo(source, url) {
            let container = document.getElementById('video-container');
            let embed;

            if (source === 'youtube') {
                embed = `<iframe class="w-full h-full" src="https://www.youtube.com/embed/${extractYouTubeId(url)}" frameborder="0" allowfullscreen></iframe>`;
            } else {
                embed = `<video class="w-full h-full" controls><source src="${url}" type="video/mp4"></video>`;
            }

            container.innerHTML = embed;
            document.getElementById('video-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('video-modal').classList.add('hidden');
            document.getElementById('video-container').innerHTML = '';
        }

        function extractYouTubeId(url) {
            const regex = /(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/;
            const match = url.match(regex);
            return match ? match[1] : '';
        }
    </script>
</x-filament::page>
