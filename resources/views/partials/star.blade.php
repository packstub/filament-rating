@php
    use Filament\Support\Enums\IconSize;
    use function Filament\Support\generate_icon_html;

    $iconSize = IconSize::tryFrom($size) ?? IconSize::Medium;
@endphp

<span class="fi-rating-star-empty">{{ generate_icon_html($emptyIcon, size: $iconSize) }}</span>
<span
    class="fi-rating-star-fill"
    style="--fi-rating-fill: {{ round($fill * 100, 2) }}%"
    @if ($alpineFill ?? null) x-bind:style="{ '--fi-rating-fill': {{ $alpineFill }} }" @endif
>{{ generate_icon_html($icon, size: $iconSize) }}</span>
