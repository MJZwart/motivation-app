<template>
    <component
        :is="modal.component"
        v-for="(modal, index) in modals"
        :key="index"
        :modal="modal.modal"
        @close="deleteModal(index)" 
    />
</template>

<script setup lang="ts">
import {modals, deleteModal} from './modalService';
</script>