@php
    use Filament\Support\Enums\IconSize;
    use function Filament\Support\generate_icon_html;

    $label = $summarizer->getLabel();
    $rows = $summarizer->getRows();
    $showPercentages = $summarizer->shouldShowPercentages();
@endphp

<div {{ $summarizer->getExtraAttributeBag()->class(['fi-ta-text-summary', 'fi-ta-rating-summary']) }}>
    @if (filled($label))
        <span @class(['fi-ta-text-summary-label', 'fi-sr-only' => method_exists($summarizer, 'isLabelHidden') && $summarizer->isLabelHidden()])>{{ $label }}</span>
    @endif

    <ul class="fi-rating fi-rating-distribution" style="{{ $summarizer->getColorStyle() }}">
        @foreach ($rows as $rating => $row)
            @php
                $percentage = round($row['percentage']);
                $rowLabel = $summarizer->getLabelFor($rating);
            @endphp

            <li
                class="fi-rating-distribution-row"
                style="{{ $summarizer->getColorStyle($rating) }}"
                @if (filled($rowLabel)) title="{{ $rowLabel }}" @endif
            >
                <span class="fi-rating-distribution-rating">
                    <span aria-hidden="true">{{ $rating }}</span>
                    <span aria-hidden="true">{{ generate_icon_html($summarizer->getIcon(), size: IconSize::Small) }}</span>
                    <span class="fi-sr-only">{{ trans_choice('filament-rating::rating.stars', $rating, ['value' => $rating]) }}{{ filled($rowLabel) ? " ({$rowLabel})" : '' }}:</span>
                </span>
                <span class="fi-rating-distribution-bar" aria-hidden="true">
                    <span style="width: {{ $row['percentage'] > 0 ? max(2, round($row['percentage'], 2)) : 0 }}%"></span>
                </span>
                <span class="fi-rating-distribution-count">
                    {{ $showPercentages ? "{$percentage}%" : $row['count'] }}
                    @unless ($showPercentages)
                        <span class="fi-sr-only">({{ $percentage }}%)</span>
                    @endunless
                </span>
            </li>
        @endforeach
    </ul>
</div>
