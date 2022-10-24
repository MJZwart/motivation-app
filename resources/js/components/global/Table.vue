<template>
    <table v-if="items && currentSort" :class="className">
        <thead>
            <tr>
                <th v-for="(field, index) in fields" :key="index">
                    <slot v-bind="field" :name="'head'">
                        <span v-if="field.sortable" class="clickable block" @click="toggleSort(field.key)">
                            {{ $t(field.label) }} 
                            <FaIcon 
                                icon="sort"  />
                        </span>
                        <span v-else>{{ $t(field.label) }}</span>
                    </slot>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in sortedItems" :key="index">
                <td v-for="(field, idx) in fields" :key="idx" :data-label="field.label +':  '">
                    <slot v-bind="{item, index}" :name="field.key">
                        {{item[field.key]}}
                    </slot>
                </td>
            </tr>
            <tr v-if="sortedItems.length == 0" role="row">
                <td :colspan="fields.length" role="cell">
                    <slot name="empty" />
                </td>
            </tr>
        </tbody>
        <!-- How to use custom templates::
            Head template:
                <template #head(key)="field">{{ content }}</template>
            Cell template
                <template #key="item">{{ content }}</template>
            Empty
                <template #empty>{{ content }}</template>
         -->
    </table>
</template>

<script setup lang="ts">
import {ref, computed, onMounted, PropType} from 'vue';
import {sortValues} from '/js/services/sortService';
import {Item, Field} from 'resources/types/global';

const props = defineProps({
    items: {
        type: Array as PropType<Array<Item>>,
        required: true,
    },
    fields: {
        type: Array as PropType<Array<Field>>,
        required: true,
    },
    options: {
        type: Array,
        required: false,
    },
    sort: {
        type: String,
        required: false,
    },
    sortAsc: {
        type: Boolean,
        required: false,
        default: true,
    },
});

onMounted(() => {
    if (props.fields)
        currentSort.value = props.sort ? props.sort : props.fields[0].key;
    currentSortDir.value = props.sortAsc ? 'asc' : 'desc';
});

const currentSortDir = ref('');
const currentSort = ref('');

const className = computed(() => {
    let className = 'table ';
    if (props.options) {
        props.options.forEach(element => {
            className += ' ' + element;
        });
    }
    return className;
});

const sortedItems = computed<Array<Item>>(() => {
    return sortValues(props.items, currentSort.value, currentSortDir.value);
});

function toggleSort(key: string) {
    key == currentSort.value ? toggleDir() : currentSort.value = key;
    return sortValues(props.items, currentSort.value, currentSortDir.value);
}
function toggleDir() {
    currentSortDir.value = currentSortDir.value == 'asc' ? 'desc' : 'asc';
}

</script>

<style lang="scss">
@import '../../../assets/scss/variables';
.table-striped tbody tr:nth-of-type(2n+1) {
    background-color: rgba(0, 0, 0, .05);
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, .09);
}
table {
    border-collapse: collapse;
}
table tbody tr {
    height: 40px;
}
table thead tr th {
    text-align: start;
}
.page-wide{
    width:100%;
}
td {
    padding: 0.5rem;
}
</style>