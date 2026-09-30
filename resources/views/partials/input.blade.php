{{--
    Interactive stars shared by the form field and the editable column. Expects $component, $state (float|null),
    $xData (the Alpine x-data expression), $wireKey, $attributes (root attributes), $groupLabel, $isReadOnly and $isRequired.
--}}
@php
    use Filament\Support\Enums\IconSize;
    use Filament\Support\Facades\FilamentAsset;
    use Illuminate\View\ComponentAttributeBag;
    use function Filament\Support\generate_icon_html;

    $stars = $component->getStars();
    $step = $component->getStep();
    $isHalf = $component->allowsHalf();
    $allowsZero = $component->shouldAllowZero();
    $isDisabled = $component->isDisabled();
    $isInteractive = $component->isInteractive();
    $isClearable = $component->isClearable();
    $size = $component->getSize();
    $iconSize = IconSize::tryFrom($size) ?? IconSize::Medium;
    $icon = $component->getIcon();
    $emptyIcon = $component->getEmptyIcon();
    $hasLabels = $component->hasLabels();
    $hasColorThresholds = filled($component->getColors());

    $tag = $isHalf ? 'span' : 'button';
    $valueText = fn (float | int $value): string => $component->getValueText($value);
    $label = $groupLabel;

    $isChecked = fn (int $value): bool => ($state !== null) && ($value === 0 ? $state === 0.0 : (((int) ceil($state) === $value) && ($state > 0)));
    $tabStop = (($state === null) || (($state === 0.0) && (! $allowsZero))) ? ($allowsZero ? 0 : 1) : (int) ceil($state);

    // Roving tabindex, mirrored by tabIndexFor() in the Alpine component once it loads.
    $radioAttributes = fn (int $value): string => $isHalf ? '' : (new ComponentAttributeBag([
        'role' => 'radio',
        'aria-checked' => $isChecked($value) ? 'true' : 'false',
        'x-bind:aria-checked' => "isChecked({$value}) ? 'true' : 'false'",
        'tabindex' => $value === $tabStop ? '0' : '-1',
        'x-bind:tabindex' => "tabIndexFor({$value})",
    ]))->toHtml();
@endphp

<div
    x-load
    x-load-src="{{ FilamentAsset::getAlpineComponentSrc('rating', 'packstub/filament-rating') }}"
    x-data="{{ $xData }}"
    wire:key="{{ $wireKey }}"
    {{
        $attributes
            ->merge([
                'style' => $component->getColorStyle($state),
                'x-bind:style' => $hasColorThresholds ? 'colorVariables()' : null,
            ], escape: false)
            ->class([
                'fi-rating',
                "fi-rating-size-{$size}",
                'fi-rating-interactive' => $isInteractive,
                'fi-disabled' => $isDisabled,
                'fi-rating-readonly' => $isReadOnly,
            ])
    }}
>
    <div
        {{
            (new ComponentAttributeBag([
                'role' => $isHalf ? 'slider' : 'radiogroup',
                'aria-label' => $label ?: null,
                'aria-disabled' => $isDisabled ? 'true' : null,
                'aria-readonly' => $isReadOnly ? 'true' : null,
                'aria-required' => $isRequired ? 'true' : null,
                'x-on:keydown' => 'onKeydown($event)',
                'x-on:pointerleave' => 'hover = null',
                'class' => 'fi-rating-stars',
            ]))->merge($isHalf ? [
                'tabindex' => $isDisabled ? null : '0',
                'aria-valuemin' => $allowsZero ? 0 : $step,
                'aria-valuemax' => $stars,
                'aria-valuenow' => $state,
                'x-bind:aria-valuenow' => 'value',
                'aria-valuetext' => $state === null ? __('filament-rating::rating.not_rated') : $valueText($state),
                'x-bind:aria-valuetext' => 'valueText()',
            ] : [], escape: false)
        }}
    >
    @if ($allowsZero)
        <{{ $tag }}
            @unless ($isHalf) type="button" @endunless
            {!! $radioAttributes(0) !!}
            aria-label="{{ __('filament-rating::rating.zero') }}"
            @disabled($isDisabled && ! $isHalf)
            x-on:click="select(0)"
            x-on:pointerenter="preview(0)"
            @class(['fi-rating-zero', 'fi-rating-zero-active' => $state === 0.0])
            x-bind:class="{ 'fi-rating-zero-active': shown === 0 }"
        >
            {{ generate_icon_html($component->getZeroIcon(), size: $iconSize) }}
        </{{ $tag }}>
    @endif

    @foreach ($component->getStarsArray() as $star)
        @php
            $fill = max(0, min(1, ($state ?? 0) - ($star - 1)));
            $alpineFill = "fill({$star})";
        @endphp

        <{{ $tag }}
            {!! $radioAttributes($star) !!}
            @unless ($isHalf)
                type="button"
                aria-label="{{ $valueText($star) }}"
                @disabled($isDisabled)
            @endunless
            x-on:click="select(valueFromPointer($event, {{ $star }}))"
            x-on:pointermove="preview(valueFromPointer($event, {{ $star }}))"
            class="fi-rating-star"
        >
            @include('filament-rating::partials.star')
        </{{ $tag }}>
    @endforeach
    </div>

    @if ($hasLabels)
        <span class="fi-rating-label" aria-hidden="true" x-text="label()">{{ $component->getLabelFor($state) }}</span>
    @endif

    @if ($isClearable && $isInteractive)
        <button
            type="button"
            class="fi-rating-clear"
            aria-label="{{ __('filament-rating::rating.clear') }}"
            title="{{ __('filament-rating::rating.clear') }}"
            tabindex="-1"
            x-on:click="clear()"
            x-show="hasValue"
            @if ($state === null) style="display: none" @endif
        >
            {{ generate_icon_html($component->getClearIcon(), size: IconSize::Small) }}
        </button>
    @endif
</div>
