@php
    use Filament\Support\Enums\Alignment;
    use Illuminate\Support\Js;

    $state = $column->normalizeRatingState($getState());
    $alignment = $getAlignment();
    $tooltip = $getTooltip($state);

    $config = [
        ...$column->getRatingInputConfig(),
        'column' => ['name' => $getName(), 'recordKey' => $getRecordKey()],
    ];
@endphp

<div
    x-on:click.stop
    @class([
        'fi-ta-icon fi-ta-rating fi-ta-rating-input',
        'fi-inline' => $isInline(),
        ($alignment instanceof Alignment) ? "fi-align-{$alignment->value}" : (is_string($alignment) ? $alignment : ''),
    ])
    @if (filled($tooltip))
        x-tooltip="{ content: @js($tooltip), theme: $store.theme }"
    @endif
>
    @include('filament-rating::partials.input', [
        'component' => $column,
        'state' => $state,
        'xData' => 'ratingFormComponent({ state: '.Js::from($state).', ...'.Js::from($config).' })',
        // The state is part of the key, so a new server value (after saving, or from an action) re-initializes the stars.
        'wireKey' => 'rating-column.'.$getName().'.'.$getRecordKey().'.'.Js::from($state).'.'.substr(md5(serialize($config)), 0, 16),
        'attributes' => $getExtraAttributeBag()->merge([
            'x-bind:class' => "{ 'fi-rating-invalid': error !== undefined, 'fi-rating-loading': isLoading }",
            'x-tooltip' => 'error === undefined ? false : { content: error, theme: $store.theme }',
        ], escape: false)->class(['fi-rating-field', 'fi-rating-column-input']),
        'groupLabel' => trim(strip_tags((string) $getLabel())),
        'isReadOnly' => false,
        'isRequired' => false,
    ])
</div>
