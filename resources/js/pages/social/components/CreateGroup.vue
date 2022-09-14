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
            <small class="form-text text-muted">{{ $t('group-description-desc') }}</small>
            <div class="form-group">
                <input id="public-checkbox" v-model="groupToCreate.is_public" type="checkbox" name="is_public" />
                <label for="public-checkbox" class="option-label">{{ $t('group-public-checkbox') }}</label>
                <small class="form-text text-muted">{{ $t('group-public-checkbox-desc') }}</small>
                <BaseFormError name="is_public" />
            </div>
            <div v-if="groupToCreate.is_public" class="form-group">
                <input
                    id="require-application-checkbox"
                    v-model="groupToCreate.require_application"
                    type="checkbox"
                    name="require_application"
                />
                <label for="require-application-checkbox" class="options-label">
                    {{ $t('group-require-application-checkbox') }}
                </label>
                <small class="form-text text-muted">{{ $t('group-require-application-checkbox-desc') }}</small>
                <BaseFormError name="require_application" />
            </div>
            <button type="submit" class="block">{{ $t('create-group') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
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
