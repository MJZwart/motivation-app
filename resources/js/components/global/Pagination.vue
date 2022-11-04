<template>
    <div class="paginated">
        <slot v-bind="paginatedItems" name="items" />
        
        <PaginationControls 
            :model-value="itemsToPaginate" 
            :items-per-page="itemsPerPage" 
            :hide-controls="hideControls" 
            @update:model-value="updatePaginated" />
    </div>
</template>

<script setup lang="ts">
import PaginationControls from './PaginationControls.vue';
import {PropType, ref, watch} from 'vue';
import {Item} from 'resources/types/global';

const props = defineProps({
    //TODO remove any and find generic typing for this component
    items: {
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        type: Array as PropType<any[]>,
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


const itemsToPaginate = ref([...props.items]);

const paginatedItems = ref([...props.items]);

watch(
    () => props.items,
    () => {
        itemsToPaginate.value = props.items.slice();
    },
);

function updatePaginated(value: Item[]) {
    paginatedItems.value = value;
}
</script>