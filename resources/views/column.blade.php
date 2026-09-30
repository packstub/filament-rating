@php
    use Filament\Support\Enums\Alignment;
    use Illuminate\View\ComponentAttributeBag;

    $state = $getState();
    $alignment = $getAlignment();
    $tooltip = $getTooltip($state);
@endphp

<div
    {{
        $getExtraAttributeBag()
            ->class([
                'fi-ta-icon fi-ta-rating',
                'fi-inline' => $isInline(),
                ($alignment instanceof Alignment) ? "fi-align-{$alignment->value}" : (is_string($alignment) ? $alignment : ''),
            ])
    }}
    @if (filled($tooltip))
        x-tooltip="{ content: @js($tooltip), theme: $store.theme }"
    @endif
>
    @if (blank($state) && filled($placeholder = $getPlaceholder()))
        <p class="fi-ta-placeholder">{{ $placeholder }}</p>
    @else
        @include('filament-rating::partials.display', [
            'component' => $column,
            'state' => $column->normalizeRatingState($state),
            'attributes' => new ComponentAttributeBag,
        ])
    @endif
</div>
