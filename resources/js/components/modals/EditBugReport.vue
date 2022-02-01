<template>
    
</template>


<script>
import Vue from 'vue';

export default {
    props: {
        incomingBug: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            bug = this.incomingBug;
        }
    },
    methods: {
        close() {
            this.bug = {};
            this.$emit('cloce');
        },
    },
}
</script>
