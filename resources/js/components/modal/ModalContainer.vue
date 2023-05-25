<template>
    <TransitionGroup name="fade">
        <component
            :is="modal.component"
            v-for="(modal, index) in modals"
            :key="index"
            :modal="modal.modal"
            @close="deleteModal(index)" 
        />
    </TransitionGroup>
</template>

<script setup lang="ts">
import {modals, deleteModal} from './modalService';
</script>

<style lang="scss">
.fade-enter-active,
.fade-leave-active {
    z-index: 9997;
    transition: opacity .5s ease;
}

.fade-enter-from,
.fade-leave-to  {
    opacity: 0;
}
</style>