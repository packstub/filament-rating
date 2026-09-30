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
}) {
    const min = allowZero ? 0 : step

    const normalize = (value) => {
        if (value === null || value === undefined || value === '') {
            return null
        }

        const number = Number.parseFloat(value)

        return Number.isNaN(number) ? null : Math.max(0, Math.min(number, stars))
    }

    const snap = (value) => Math.max(min, Math.min(stars, Math.round(value / step) * step))

    return {
        state,

        hover: null,

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
            return this.value === null ? notRatedText : valueTextTemplate.replace(':value', String(this.value))
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

        preview(value) {
            if (isInteractive) {
                this.hover = value
            }
        },

        select(value) {
            if (!isInteractive) {
                return
            }

            this.hover = null
            this.state = isClearable && this.value === value ? null : value
        },

        clear() {
            if (!isInteractive) {
                return
            }

            this.hover = null
            this.state = null
        },

        focusChecked() {
            this.$nextTick(() => this.$root.querySelector('[role="radio"][tabindex="0"]')?.focus())
        },

        onKeydown(event) {
            if (!isInteractive) {
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
