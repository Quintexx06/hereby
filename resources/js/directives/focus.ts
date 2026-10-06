import type { Directive } from 'vue';

/** v-focus — focus an element on mount unless bound to `false`. */
export const focus: Directive<HTMLElement, boolean | undefined> = {
    mounted(el, binding) {
        if (binding.value !== false) {
            el.focus();
        }
    },
};
