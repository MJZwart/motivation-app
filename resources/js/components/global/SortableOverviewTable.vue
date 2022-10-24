<template>
    <div v-if="items">
        <!-- Header field which shows only sortable fields -->
        <div class="field-header m-1 mt-2">
            <template v-for="(fieldGroup, index) in fields.fieldGroups" :key="index">
                <div :class="'width-'+fieldGroup.width">
                    <template v-for="(subField, idx) in fieldGroup.fields" :key="idx">
                        <slot v-bind="subField" :name="'head'">
                            <span v-if="subField.sortable" class="clickable block header" @click="toggleSort(subField.key)">
                                {{ $t(subField.label) }} 
                                <FaIcon icon="sort"  />
                            </span>
                        </slot>
                    </template>
                </div>
            </template>
        </div>

        <!-- Content field -->
        <template v-for="(item, idx) in sortedItems" :key="idx">
            <div class="content-block" :class="{clickable: clickToExtend}" @click="toggleExtend(idx)">
                <div class="overview-field-item">
                    <div 
                        v-for="(fieldGroup, index) in fields.fieldGroups" 
                        :key="index" 
                        class="field-group" 
                        :class="'width-'+fieldGroup.width"
                    >
                        <slot v-bind="{item, index}" :name="fieldGroup.key">
                            <h5 v-if="fieldGroup.label">{{ $t(fieldGroup.label) }}</h5>
                        </slot>
                        <p v-for="field in fieldGroup.fields" :key="field.key">
                            <slot v-if="!field.hidden" v-bind="{item, index}" :name="field.key">
                                <b>{{$t(field.label)}}:</b> {{parseItem(item[field.key])}}
                            </slot>
                        </p>
                    </div>
                </div>
                <!-- Content field for extra details, available by clicking on it -->
                <div v-show="isExtended(idx) && fields.extend" class="overview-field-details">
                    <div 
                        v-for="(field, index) in fields.extend" 
                        :key="index" 
                        class="field-group"
                    >
                        <slot v-if="!field.hidden" v-bind="{item, index}" :name="field.key">
                            <b>{{$t(field.label)}}:</b> {{parseItem(item[field.key])}}
                        </slot>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import {Item, OverviewFieldGroups} from 'resources/types/global';
import {ref, computed, PropType} from 'vue';
import {sortValues} from '/js/services/sortService';
import {isDateItem, parseDateTime} from '/js/services/dateService';

const props = defineProps({
    items: {
        type: Array as PropType<Item[]>,
        required: true,
    },
    fields: {
        type: Object as PropType<OverviewFieldGroups>,
        required: true,
    },
    clickToExtend: {
        type: Boolean,
        default: false,
    },
    singleExtend: {
        type: Boolean,
        default: false,
    },
});

const extendedIndexes = ref<number[]>([]);
const singleExtendedIndex = ref<number | null>(null);

const currentSortDir = ref('');
const currentSort = ref('');

function isExtended(index: number) {
    if (props.singleExtend) return singleExtendedIndex.value === index;
    else return extendedIndexes.value.includes(index);
}

function toggleExtend(index: number) {
    if (!props.clickToExtend) return;
    if (props.singleExtend) 
        if (singleExtendedIndex.value === index) singleExtendedIndex.value = null;
        else singleExtendedIndex.value = index;
    else {
        const existingIdx = extendedIndexes.value.indexOf(index);
        if (existingIdx == -1)
            extendedIndexes.value.push(index);
        else
            extendedIndexes.value.splice(existingIdx, 1);
    }
}

function parseItem(item: unknown) {
    if (typeof item !== 'string') return item;
    if (isDateItem(item)) return parseDateTime(item);
    else return item;
}

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
.striped .content-block:nth-of-type(2n+1) {
    background-color: rgba(0, 0, 0, .04);
}
.content-block.clickable:hover {
    background-color: rgba(0, 0, 0, .1);
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