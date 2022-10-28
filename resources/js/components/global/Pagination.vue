<template>
    <div class="paginated">
        <slot v-bind="paginatedItems" name="items" />
        <div v-if="controlsVisible" class="controls">
            <span class="range">{{ $t('pagination-item-of', {offset: (offset + 1), range: range, max: max})}}</span>
            <div class="control-buttons">
                <button class="previous" :class="{disabled: offset < 1}" @click="previous()">
                    <FaIcon icon="angle-left" /> {{$t('previous')}}
                </button>
                <button class="next" :class="{disabled: range === max}" @click="next()">
                    {{$t('next')}} <FaIcon icon="angle-right" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, PropType, ref, watch} from 'vue';
import {Item} from 'resources/types/global';

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
    },
    hideControls: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const paginatedItems = computed(() => paginate(props.items, offset.value));

const offset = ref(0);
const max = ref(props.items.length);

const range = computed(() => {
    const calcRange = max.value - offset.value;
    return calcRange > 10 ? offset.value + 10 : offset.value + calcRange;
});

const controlsVisible = computed(() => {
    if (props.hideControls)
        return max.value > 10;
    return true;
});

function paginate(array: Item[], offset = 0) {
    const lastPage = array.length - offset < 10;
    return array.slice(offset, lastPage ? undefined : offset + 10);
}

function next() {
    if (range.value === max.value) return;
    offset.value = offset.value + 10;
}
function previous() {
    if (offset.value < 1) return;
    offset.value = offset.value - 10;
}

watch(
    () => props.items,
    () => {
        offset.value = 0;
        max.value = props.items.length;
    },
);
</script>

<style lang="scss" scoped>
.paginated {
    .controls {
        display: flex;
        flex-direction: column;
        .range {
            margin-left: auto;
            margin-right: auto;
        }
        .control-buttons {
            margin-left: auto;
            margin-right: auto;
            button {
                margin: 3px;
                border: none;
                transition: box-shadow 0.15s ease-in-out;
            }
            button:hover {
                border: none;
                box-shadow: var(--basic-shadow-2);
                transition: box-shadow 0.15s ease-in-out;
            }
            button.disabled {
                background-color: var(--primary);
                color: var(--text-muted);
            }
            button.disabled:hover {
                background-color: var(--primary);
                box-shadow: none;
            }
        }

    }
}
</style>