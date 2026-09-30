@php
    use Illuminate\View\ComponentAttributeBag;

    $state = $getState();
    $tooltip = $getTooltip($state);
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div
        {{ $getExtraAttributeBag()->class(['fi-in-rating']) }}
        @if (filled($tooltip))
            x-tooltip="{ content: @js($tooltip), theme: $store.theme }"
        @endif
    >
        @if (blank($state) && filled($placeholder = $getPlaceholder()))
            <p class="fi-in-placeholder">{{ $placeholder }}</p>
        @else
            @include('filament-rating::partials.display', [
                'component' => $entry,
                'state' => $entry->normalizeRatingState($state),
                'attributes' => new ComponentAttributeBag,
            ])
        @endif
    </div>
</x-dynamic-component>
