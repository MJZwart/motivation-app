<template>
    //
</template>

<script setup lang="ts">
import {onMounted} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
const taskStore = useTaskStore();

onMounted(() => {
    taskStore.getTaskCompletions();
})
</script>