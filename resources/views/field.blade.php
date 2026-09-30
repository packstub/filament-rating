@php
    use Filament\Support\Enums\IconSize;
    use Filament\Support\Facades\FilamentAsset;
    use Illuminate\View\ComponentAttributeBag;
    use function Filament\Support\generate_icon_html;

    $statePath = $getStatePath();
    $stars = $getStars();
    $step = $getStep();
    $isHalf = $allowsHalf();
    $allowsZero = $shouldAllowZero();
    $isDisabled = $isDisabled();
    $isReadOnly = $isReadOnly();
    $isInteractive = $isInteractive();
    $isClearable = $isClearable();
    $size = $getSize();
    $iconSize = IconSize::tryFrom($size) ?? IconSize::Medium;
    $icon = $getIcon();
    $emptyIcon = $getEmptyIcon();
    $state = $field->normalizeRatingState($getState());
    $label = trim(strip_tags((string) $getLabel()));

    $config = [
        'stars' => $stars,
        'step' => $step,
        'allowZero' => $allowsZero,
        'isClearable' => $isClearable,
        'isInteractive' => $isInteractive,
        'valueTextTemplate' => trans_choice('filament-rating::rating.value_of_max', $stars, ['value' => ':value', 'max' => $stars]),
        'notRatedText' => __('filament-rating::rating.not_rated'),
    ];

    $tag = $isHalf ? 'span' : 'button';

    $valueText = fn (float | int $value): string => trans_choice('filament-rating::rating.value_of_max', $stars, [
        'value' => rtrim(rtrim(number_format($value, 1, '.', ''), '0'), '.'),
        'max' => $stars,
    ]);

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

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-load
        x-load-src="{{ FilamentAsset::getAlpineComponentSrc('rating', 'packstub/filament-rating') }}"
        x-data="ratingFormComponent({
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            ...@js($config),
        })"
        wire:key="{{ $statePath }}.rating.{{ substr(md5(serialize([$config, $size])), 0, 16) }}"
        {{
            $getExtraAttributeBag()
                ->merge([
                    'id' => $getId(),
                    'style' => $getColorStyle(),
                ], escape: false)
                ->class([
                    'fi-rating',
                    'fi-rating-field',
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
                    'aria-required' => $isRequired() ? 'true' : null,
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
                {{ generate_icon_html($getZeroIcon(), size: $iconSize) }}
            </{{ $tag }}>
        @endif

        @foreach ($getStarsArray() as $star)
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
                {{ generate_icon_html($getClearIcon(), size: IconSize::Small) }}
            </button>
        @endif
    </div>
</x-dynamic-component>
