<template>
    <div class="p-1">
        <button class="block" @click="showManageTemplateModal = true">{{$t('manage-templates')}}</button>
        <Modal 
            :show="showManageTemplateModal" 
            :title="$t('manage-templates')" 
            :header=false
            :footer=false  
            class="l"
            @close="showManageTemplateModal = false"
        >
            <template #header>
                <div class="modal-header">
                    <h5>
                        {{ $t('manage-templates') }}
                        <Tutorial tutorial="Templates" colorVariant="white" />
                    </h5>
                    <button class="close" @click="$emit('close')">×</button>
                </div>
            </template>
            <button class="block" @click="showNewTemplate = true">{{$t('new-template')}}</button>
            <NewTemplate v-if="showNewTemplate" @close="showNewTemplate = false" @submit="createNewTemplate" />
            <h5>{{$t('templates')}}</h5>
            <div class="templates">
                <template v-for="(template, index) in templates" :key="index">
                    <div class="template">
                        <div class="template-sidebar" :class="`diff-${template.difficulty}`" />
                        <div class="template-content">
                            <p><b>{{$t('name')}}:</b> {{template.name}}</p>
                            <p><b>{{$t('description')}}:</b> {{template.description}}</p>
                            <p><b>{{$t('type')}}:</b> {{$t(template.type)}}</p>
                        </div>
                    </div>
                </template>
            </div>
        </Modal>
    </div>
</template>

<script setup lang="ts">
import NewTemplate from './NewTemplate.vue';
import type {Template} from 'resources/types/task';
import {onMounted, ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import Tutorial from '/js/components/global/Tutorial.vue';

const taskStore = useTaskStore();

const templates = ref<Template[]>([]);

onMounted(async() => {
    templates.value = await taskStore.getTemplates();
});

const showManageTemplateModal = ref(false);
const showNewTemplate = ref(false);

async function createNewTemplate(newTemplate: Template) {
    templates.value = await taskStore.storeTemplate(newTemplate);
    showNewTemplate.value = false;
}
</script>