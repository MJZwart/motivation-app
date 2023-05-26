<template>
    <button 
        class="block" 
        @click="showManageTemplate">
        {{$t('manage-templates')}}
    </button>
</template>

<script setup lang="ts">
import {showModal} from '/js/components/modal/modalService';
import ManageTemplates from './ManageTemplates.vue';

function showManageTemplate() {
    showModal(null, ManageTemplates, 'manage-templates');
}
</script>