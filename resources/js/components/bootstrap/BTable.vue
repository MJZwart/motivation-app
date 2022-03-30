<template>
    <table v-if="items && currentSort" :class="className">
        <thead>
            <tr>
                <th v-for="(field, index) in fields" :key="index">
                    <slot v-bind="field" :name="headSlotName">
                        <span v-if="field.sortable" class="clickable block" @click="toggleSort(field.key)">
                            {{ field.label }} 
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-up" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.5 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L11 2.707V14.5a.5.5 0 0 0 .5.5zm-7-14a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L4 13.293V1.5a.5.5 0 0 1 .5-.5z"/>
                            </svg>
                        </span>
                        <span v-else>{{ field.label }}</span>
                    </slot>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in sortedItems" :key="index">
                <td v-for="(field, index) in fields" :key="index">
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

<script>
export default {
    props: {
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
    },
    data() {
        return {
            currentSortDir: null,
            currentSort: null,
        }
    },
    mounted() {
        if (this.fields)
            this.currentSort = this.sort ? this.sort : this.fields[0].key;
        this.currentSortDir = this.sortAsc ? 'asc' : 'desc';
    },
    computed: {
        className() {
            let className = 'table ';
            if (this.options) {
                this.options.forEach(element => {
                    className += ' ' + element;
                });
            }
            return className;
        },
        sortedItems() {
            return this.sortItems();
        },
        headSlotName() {
            return this.$scopedSlots['head()'] ? 'head()' : 'head';
        },
    },
    methods: {
        toggleSort(key) {
            key == this.currentSort ? this.toggleDir() : this.currentSort = key;
            this.sortItems();
        },
        toggleDir() {
            this.currentSortDir = this.currentSortDir == 'asc' ? 'desc' : 'asc';
        },
        sortItems() {
            return this.items.slice().sort(this.compareValues(this.currentSort, this.currentSortDir));
        },
        compareValues(key, order = 'asc') {
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
        },
    },
}
</script>