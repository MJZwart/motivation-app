<template>
    <div class="detailed-table">
        <div class="row top-row">
            <div v-for="(field, index) in topRowFields" :key="index" class="col">
                <span class="header">{{field.label}}</span>
                <slot v-bind="{item, index}" :name="field.key">
                    {{item[field.key]}}
                </slot>
            </div>
        </div>
        <div class="row bottom-row">
            <div v-for="(field, index) in bottomRowFields" :key="index" class="col">
                <span class="header">{{field.label}}</span>
                <slot v-bind="{item, index}" :name="field.key">
                    <span v-for="(detail, idx) in item[detailKey]" :key="idx" class="body">{{detail[field.key]}}</span>
                </slot>
            </div>
        </div>
    </div>
</template>

<script setup>

defineProps({
    item: {
        type: Object,
        required:true,
    },
    topRowFields: {
        type: Array,
        required: true,
    },
    bottomRowFields: {
        type: Array,
        required: true,
    },
    detailKey: {
        type: String,
        required: true,
    },
})
</script>

<style lang="scss">
// .detailed-table{
//     border: 1px solid grey;
//     .row{
//         background-color: white;
//         box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.25);
//         margin-left: 0;
//         padding: 0.3rem;
//         .col{
//             padding-right: 5px;
//             padding-left: 5px;
//             .header {
//                 font-weight:600;
//                 display: block;
//             }
//             .body {
//                 display: block;
//             }
//         }
//     }
// }
</style>