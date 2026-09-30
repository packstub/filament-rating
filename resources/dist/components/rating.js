// packstub/filament-rating: Alpine component for the Rating form field. Hand-written ES module, no build step.
export default function ratingFormComponent({
    state,
    stars,
    step,
    allowZero,
    isClearable,
    isInteractive,
    valueTextTemplate,
    notRatedText,
    labels = {},
    labelledTemplate = ':label, :value',
    colorThresholds = [],
    baseColor = {},
    column = null,
}) {
    const min = allowZero ? 0 : step

    const normalize = (value) => {
        if (value === null || value === undefined || value === '') {
            return null
        }

        const number = Number.parseFloat(value)

        return Number.isNaN(number) ? null : Math.max(0, Math.min(number, stars))
    }

    const labelKey = (value) => String(Math.round(value * 10) / 10)

    const labelFor = (value) => (value === null ? null : (labels[labelKey(value)] ?? null))

    const snap = (value) => Math.max(min, Math.min(stars, Math.round(value / step) * step))

    return {
        state,

        hover: null,

        error: undefined,

        isLoading: false,

        // In a RatingInputColumn, each change is saved to the record right away.
        init() {
            if (!column) {
                return
            }

            let isReverting = false

            this.$watch('state', async (state, previousState) => {
                if (isReverting) {
                    isReverting = false

                    return
                }

                this.isLoading = true

                const response = await this.$wire.updateTableColumnState(column.name, column.recordKey, state)

                this.error = response?.error ?? undefined

                if (this.error !== undefined) {
                    isReverting = true
                    this.state = previousState
                }

                this.isLoading = false
            })
        },

        get value() {
            return normalize(this.state)
        },

        get shown() {
            return this.hover ?? this.value
        },

        get hasValue() {
            return this.value !== null
        },

        valueText() {
            if (this.value === null) {
                return notRatedText
            }

            const text = valueTextTemplate.replace(':value', String(this.value))
            const label = labelFor(this.value)

            return label === null ? text : labelledTemplate.replace(':label', label).replace(':value', text)
        },

        // The label of the hovered or picked rating, shown next to the stars.
        label() {
            return labelFor(this.shown) ?? ''
        },

        // With colors(), the fill color follows the hovered or picked rating.
        colorVariables() {
            let variables = baseColor

            for (const [min, thresholdVariables] of colorThresholds) {
                if (this.shown !== null && this.shown >= min) {
                    variables = thresholdVariables
                }
            }

            return variables
        },

        fill(star) {
            return `${Math.max(0, Math.min(1, (this.shown ?? 0) - (star - 1))) * 100}%`
        },

        isChecked(value) {
            if (this.value === null) {
                return false
            }

            return value === 0 ? this.value === 0 : Math.ceil(this.value) === value && this.value > 0
        },

        // Roving tabindex: the checked radio is the tab stop, or the first one when nothing is picked.
        tabIndexFor(value) {
            if (this.value === null || (this.value === 0 && !allowZero)) {
                return value === (allowZero ? 0 : 1) ? 0 : -1
            }

            return this.isChecked(value) ? 0 : -1
        },

        isRtl() {
            return getComputedStyle(this.$root).direction === 'rtl'
        },

        valueFromPointer(event, star) {
            if (step >= 1) {
                return star
            }

            const rect = event.currentTarget.getBoundingClientRect()
            const isStartHalf = this.isRtl()
                ? event.clientX > rect.left + rect.width / 2
                : event.clientX < rect.left + rect.width / 2

            return isStartHalf ? star - 0.5 : star
        },

        canChange() {
            return isInteractive && !this.isLoading
        },

        preview(value) {
            if (this.canChange()) {
                this.hover = value
            }
        },

        select(value) {
            if (!this.canChange()) {
                return
            }

            this.hover = null
            this.state = isClearable && this.value === value ? null : value
        },

        clear() {
            if (!this.canChange()) {
                return
            }

            this.hover = null
            this.state = null
        },

        focusChecked() {
            this.$nextTick(() => this.$root.querySelector('[role="radio"][tabindex="0"]')?.focus())
        },

        onKeydown(event) {
            if (!this.canChange()) {
                return
            }

            const forward = this.isRtl() ? 'ArrowLeft' : 'ArrowRight'
            const backward = this.isRtl() ? 'ArrowRight' : 'ArrowLeft'
            const current = this.value

            let next

            switch (event.key) {
                case forward:
                case 'ArrowUp':
                    next = current === null ? min || step : snap(current + step)
                    break
                case backward:
                case 'ArrowDown':
                    next = current === null ? min : snap(current - step)
                    break
                case 'Home':
                    next = min
                    break
                case 'End':
                    next = stars
                    break
                case 'Delete':
                case 'Backspace':
                    if (!isClearable) {
                        return
                    }

                    next = null
                    break
                default:
                    return
            }

            event.preventDefault()

            this.hover = null
            this.state = next

            this.focusChecked()
        },
    }
}
