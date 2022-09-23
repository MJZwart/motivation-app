<template>
    <div v-if="items">
        <!-- Header field which shows only sortable fields -->
        <div class="field-header m-1 mt-2">
            <template v-for="(fieldGroup, index) in fields" :key="index">
                <div :class="'width-'+fieldGroup.width">
                    <template v-for="(subField, idx) in fieldGroup.fields" :key="idx">
                        <slot v-bind="subField" :name="'head'">
                            <span v-if="subField.sortable" class="clickable block header" @click="toggleSort(subField.key)">
                                {{ subField.label }} 
                                <FaIcon icon="sort"  />
                            </span>
                        </slot>
                    </template>
                </div>
            </template>
        </div>

        <!-- Content field -->
        <template v-for="(item, idx) in sortedItems" :key="idx">
            <div class="content-block" :class="{clickable: clickToExtend}" @click="test = !test">
                <div class="overview-field-item">
                    <div 
                        v-for="(fieldGroup, index) in fields" 
                        :key="index" 
                        class="field-group" 
                        :class="'width-'+fieldGroup.width"
                    >
                        <slot v-bind="{item, index}" :name="fieldGroup.key">
                            <h5>{{fieldGroup.label}}</h5>
                        </slot>
                        <p v-for="field in fieldGroup.fields" :key="field.key">
                            <slot v-if="!field.hidden" v-bind="{item, index}" :name="field.key">
                                <b>{{field.label}}:</b> {{item[field.key]}}
                            </slot>
                        </p>
                    </div>
                </div>
                <div v-if="test" class="overview-field-details">
                    <!-- {{item}} -->
                    {{fields}}
                    <!-- <slot v-bind="{item, idx}" :name="'details'">
                        <div v-for="fieldGroup in fields" :key="fieldGroup.key">
                            <p v-for="field in fieldGroup.fields" :key="field.key">
                                <slot v-if="!field.hidden" v-bind="{item, index}" :name="field.key">
                                    <b>{{field.label}}:</b> {{item[field.key]}}
                                </slot>
                            </p>
                            <slot v-if="fieldGroup.details" v-bind="{item, idx}" :name="fieldGroup.key">
                                <b>{{fieldGroup.label}}:</b> {{item[field.key]}}
                            </slot>
                        </div>
                    </slot> -->
                </div>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import {Item, OverviewField} from 'resources/types/global';
import {ref, computed, PropType} from 'vue';
import {sortValues} from '/js/services/sortService';

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
    },
    fields: {
        type: Array as PropType<OverviewField[]>,
        required: true,
    },
    clickToExtend: {
        type: Boolean,
        default: false,
    },
});

const test = ref(false);

const currentSortDir = ref('');
const currentSort = ref('');

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
.overview-field-item {
    display: flex;
}
.content-block:nth-of-type(2n+1) {
    background-color: rgba(0, 0, 0, .05);
}
.overview-field-item.clickable:hover {
    background-color: rgba(0, 0, 0, .09);
}
.field-header {
    display: flex;
}
@media(max-width: 600px) {
    .overview-field-item {
        flex-direction: column;
    }
    .field-group {
        width: 100% !important;
    }
}
// .overview-field-details{
    
// }
</style>