{{-- Read-only stars shared by the column, the entry and the summarizer. Expects $component and $state (float|null). --}}
@php
    use Filament\Support\Enums\IconSize;
    use Illuminate\View\ComponentAttributeBag;
    use function Filament\Support\generate_icon_html;

    $size = $component->getSize();
    $icon = $component->getIcon();
    $emptyIcon = $component->getEmptyIcon();
    $label = $component->getRatingLabel($state);
    $count = $component->getCount();
@endphp

<div
    {{ ($attributes ?? new ComponentAttributeBag)->class(['fi-rating', 'fi-rating-display', "fi-rating-size-{$size}"])->merge(['style' => $component->getColorStyle($state)], escape: false) }}
>
    <span class="fi-rating-stars" role="img" aria-label="{{ $label }}">
        @if ($component->shouldAllowZero())
            <span @class(['fi-rating-zero', 'fi-rating-zero-active' => $state === 0.0])>
                {{ generate_icon_html($component->getZeroIcon(), size: IconSize::tryFrom($size) ?? IconSize::Medium) }}
            </span>
        @endif

        @foreach ($component->getFills($state) as $star => $fill)
            <span class="fi-rating-star">
                @include('filament-rating::partials.star')
            </span>
        @endforeach
    </span>

    @if ($component->shouldShowValue() && ($state !== null))
        <span class="fi-rating-value" aria-hidden="true">{{ $component->formatRatingValue($state) }}</span>
    @endif

    @if ($component->shouldShowLabel() && filled($ratingLabel = $component->getLabelFor($state)))
        <span class="fi-rating-label" aria-hidden="true">{{ $ratingLabel }}</span>
    @endif

    @if ($count !== null)
        <span class="fi-rating-count">{{ __('filament-rating::rating.count', ['count' => $count]) }}</span>
    @endif
</div>
