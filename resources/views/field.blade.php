@php
    use Illuminate\View\ComponentAttributeBag;

    $statePath = $getStatePath();
    $config = $getRatingInputConfig();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @include('filament-rating::partials.input', [
        'component' => $field,
        'state' => $field->normalizeRatingState($getState()),
        'xData' => 'ratingFormComponent({ state: $wire.'.$applyStateBindingModifiers("\$entangle('{$statePath}')").', ...'.\Illuminate\Support\Js::from($config).' })',
        'wireKey' => $statePath.'.rating.'.substr(md5(serialize([$config, $getSize()])), 0, 16),
        'attributes' => $getExtraAttributeBag()->merge(['id' => $getId()], escape: false)->class(['fi-rating-field']),
        'groupLabel' => trim(strip_tags((string) $getLabel())),
        'isReadOnly' => $isReadOnly(),
        'isRequired' => $isRequired(),
    ])
</x-dynamic-component>
