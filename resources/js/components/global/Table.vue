<template>
    <table v-if="items && currentSort" :class="className">
        <thead>
            <tr>
                <th v-for="(field, index) in fields" :key="index">
                    <slot v-bind="field" :name="'head'">
                        <span v-if="field.sortable" class="clickable block" @click="toggleSort(field.key)">
                            {{ field.label }} 
                            <FaIcon 
                                icon="sort"  />
                        </span>
                        <span v-else>{{ field.label }}</span>
                    </slot>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in sortedItems" :key="index">
                <td v-for="(field, idx) in fields" :key="idx">
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

<script setup>
import {ref, computed, onMounted} from 'vue';
const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    fields: {
        type: Array,
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
const sortedItems = computed(() => {
    return sortItems();
});

function toggleSort(key) {
    key == currentSort.value ? toggleDir() : currentSort.value = key;
    sortItems();
}
function toggleDir() {
    currentSortDir.value = currentSortDir.value == 'asc' ? 'desc' : 'asc';
}
function sortItems() {
    return props.items.slice().sort(compareValues(currentSort.value, currentSortDir.value));
}
function compareValues(key, order = 'asc') {
    // eslint-disable-next-line complexity
    return function innerSort(a, b) {
        if (!Object.prototype.hasOwnProperty.call(a, key) || !Object.prototype.hasOwnProperty.call(b, key))
            return 0;

        const varA = (typeof a[key] === 'string') ? a[key].toUpperCase() : a[key];
        const varB = (typeof b[key] === 'string') ? b[key].toUpperCase() : b[key];

        let comparison = 0;
        if (varA > varB) comparison = 1;
        else if (varA < varB) comparison = -1;

        return ((order === 'desc') ? (comparison * -1) : comparison);
    };
}
</script>