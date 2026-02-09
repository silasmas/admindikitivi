@php
    $value = $getState();
    $label = $getLabel() ?? 'Image';
    $height = $getStatePath() === 'cover_url' ? 200 : 150;
    $logoUrl = asset('assets/images/logo.png');
    $imageUrl = filled($value)
        ? (str_starts_with((string) $value, 'http') ? $value : \Illuminate\Support\Facades\Storage::disk('public')->url($value))
        : $logoUrl;
@endphp

<div>
    <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">{{ $label }}</div>
    <div class="rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800" style="max-width: 300px; height: {{ $height }}px;">
        <img src="{{ $imageUrl }}" alt="{{ $label }}" class="w-full h-full object-cover"
             style="width: 100%; height: {{ $height }}px;"
             onerror="this.onerror=null; this.src='{{ $logoUrl }}';">
    </div>
</div>
