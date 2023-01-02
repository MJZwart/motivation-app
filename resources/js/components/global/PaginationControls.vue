<template>
    <div v-if="controlsVisible" class="controls">
        <span class="range">{{ $t('pagination-item-of', {offset: (offset + 1), range: range, max: max})}}</span>
        <div class="control-buttons">
            <button class="previous" :class="{disabled: offset < 1}" @click="previous()">
                <Icon :icon="ARROW_LEFT" />
                {{$t('previous')}}
            </button>
            <button class="next" :class="{disabled: range === max}" @click="next()">
                {{$t('next')}} <Icon :icon="ARROW_RIGHT" />
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, PropType, ref, watch} from 'vue';
import {Item} from 'resources/types/global';
import {ARROW_LEFT, ARROW_RIGHT} from '/js/constants/iconConstants';

const props = defineProps({
    modelValue: {
        type: Array as PropType<Item[]>,
        required: true,
    },
    itemsPerPage: {
        type: Number,
        required: false,
        default: 10,
    },
    hideControls: {
        type: Boolean,
        required: false,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

onMounted(() => {
    paginate(props.modelValue, offset.value);
});

function paginate(array: Item[], offset = 0) {
    const lastPage = array.length - offset < props.itemsPerPage;
    const paginated = array.slice(offset, lastPage ? undefined : offset + props.itemsPerPage);
    emit('update:modelValue', paginated);
}

const offset = ref(0);
const max = ref(props.modelValue.length);

const range = computed(() => {
    const calcRange = max.value - offset.value;
    return calcRange > props.itemsPerPage ? offset.value + props.itemsPerPage : offset.value + calcRange;
});

const controlsVisible = computed(() => {
    if (props.hideControls)
        return max.value > props.itemsPerPage;
    return true;
});

function next() {
    if (range.value === max.value) return;
    offset.value = offset.value + props.itemsPerPage;
    paginate(props.modelValue, offset.value);
}
function previous() {
    if (offset.value < 1) return;
    offset.value = offset.value - props.itemsPerPage;
    paginate(props.modelValue, offset.value);
}

watch(
    () => props.modelValue,
    () => {
        offset.value = 0;
        max.value = props.modelValue.length;
        paginate(props.modelValue, offset.value);
    },
);
</script>