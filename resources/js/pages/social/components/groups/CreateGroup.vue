<template>
    <div>
        <form @submit.prevent="createGroup">
            <SimpleInput
                id="name"
                v-model="groupToCreate.name"
                name="name"
                :label="$t('name')"
                :placeholder="$t('name')"
            />
            <small class="form-text text-muted">{{ $t('group-name-desc') }}</small>

            <SimpleTextarea
                id="description"
                v-model="groupToCreate.description"
                name="description"
                :label="$t('group-desc')"
                :placeholder="$t('description')"
            />
            <small class="form-text text-muted mb-3">{{ $t('group-description-desc') }}</small>

            <SimpleFormCheckbox 
                id="public-checkbox" 
                v-model="groupToCreate.is_public" 
                name="is_public" 
                class="mb-0"
                :label="$t('group-public-checkbox')" />
            <small class="form-text text-muted mb-3">{{ $t('group-public-checkbox-desc') }}</small>
            
            <SimpleFormCheckbox 
                v-if="groupToCreate.is_public"
                id="require-application-checkbox" 
                v-model="groupToCreate.require_application" 
                name="require_application" 
                class="mb-0"
                :label="$t('group-require-application-checkbox')" />
            <small class="form-text text-muted mb-3">{{ $t('group-require-application-checkbox-desc') }}</small>
            
            <SubmitButton id="create-new-group" class="block">{{ $t('create-group') }}</SubmitButton>
            <button type="button" class="block button-cancel" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {useGroupStore} from '/js/store/groupStore.js';
import type {NewGroup} from 'resources/types/group';
const groupStore = useGroupStore();

const emit = defineEmits(['reloadGroups', 'close']);

const initialData = {
    name: '',
    description: '',
    is_public: false,
    require_application: false,
};

const groupToCreate = ref<NewGroup>({...initialData});

async function createGroup() {
    await groupStore.createGroup(groupToCreate.value);
    emit('reloadGroups');
    close();
}
function close() {
    resetForm();
    emit('close');
}
function resetForm() {
    Object.assign(groupToCreate, initialData);
}
</script>
