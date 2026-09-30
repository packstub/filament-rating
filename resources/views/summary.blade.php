@php
    $label = $summarizer->getLabel();
    $state = $summarizer->normalizeRatingState($summarizer->getState());
@endphp

<div {{ $summarizer->getExtraAttributeBag()->class(['fi-ta-text-summary', 'fi-ta-rating-summary']) }}>
    @if (filled($label))
        <span @class(['fi-ta-text-summary-label', 'fi-sr-only' => method_exists($summarizer, 'isLabelHidden') && $summarizer->isLabelHidden()])>{{ $label }}</span>
    @endif

    @include('filament-rating::partials.display', [
        'component' => $summarizer,
        'state' => $state,
    ])
</div>
