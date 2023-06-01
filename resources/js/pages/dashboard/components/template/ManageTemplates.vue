<template>
    
    <button v-if="!showNewTemplate" class="block" @click="showNewTemplate = true">{{$t('new-template')}}</button>
    <CreateNewTemplate 
        v-if="showNewTemplate" 
        :templateToEdit="templateToEdit"
        @close="cancelNewEditTemplate" 
        @submit="createNewTemplate" 
        @edit="submitEditTemplate"
    />
    <div v-else>
        <h5>{{$t('templates')}}<Tutorial tutorial="Templates" /></h5>
        <div class="templates">
            <template v-for="(template, index) in templates" :key="index">
                <div class="template">
                    <div class="template-sidebar" :class="`diff-${template.difficulty}`" />
                    <div class="template-content">
                        <p class="d-flex">
                            <b>{{template.name}} </b> ({{$t(template.type)}})
                            <span class="ml-auto mr-2">
                                <Tooltip :text="$t('edit-template')" placement="left">
                                    <Icon 
                                        :icon="EDIT"
                                        class="edit-icon small"
                                        @click="editTemplate(template)" />
                                </Tooltip>
                                <Tooltip :text="$t('delete-template')" placement="left">
                                    <Icon 
                                        :icon="TRASH"
                                        class="delete-icon small red"
                                        @click="deleteTemplate(template)" />
                                </Tooltip>
                            </span>
                        </p>
                        <p class="task-description">{{template.description}}</p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup lang="ts">
import CreateNewTemplate from './NewTemplate.vue';
import Tutorial from '/js/components/global/Tutorial.vue';
import {onMounted, ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import {useI18n} from 'vue-i18n'
import type {NewTemplate, Template} from 'resources/types/task';
import {EDIT, TRASH} from '/js/constants/iconConstants';
const {t} = useI18n() // use as global scope

const taskStore = useTaskStore();

const templates = ref<Template[]>([]);

onMounted(async() => {
    templates.value = await taskStore.getTemplates();
});

defineEmits(['close']);

const showNewTemplate = ref(false);
const templateToEdit = ref<Template | undefined>(undefined);

async function createNewTemplate(newTemplate: NewTemplate) {
    templates.value = await taskStore.storeTemplate(newTemplate);
    showNewTemplate.value = false;
}

function editTemplate(template: Template) {
    templateToEdit.value = template;
    showNewTemplate.value = true;
}
async function submitEditTemplate(template: Template) {
    templates.value = await taskStore.updateTemplate(template);
    cancelNewEditTemplate();
}
function cancelNewEditTemplate() {
    if (templateToEdit.value)
        templateToEdit.value = undefined;
    showNewTemplate.value = false;
}
async function deleteTemplate(template: Template) {
    if (window.confirm(t('confirm-delete-template', template.name)))
        templates.value = await taskStore.deleteTemplate(template.id);
}
</script>

<style lang="scss" scoped>
.show{
    animation: slide-in-top 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
}
</style>