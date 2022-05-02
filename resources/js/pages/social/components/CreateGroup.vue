<template>
    <div>
        <form @submit.prevent="createGroup">
            <Input
                id="name"
                v-model="groupToCreate.name"
                name="name"
                :label="$t('name')"
                :placeholder="$t('name')" />
            <small class="form-text text-muted">{{$t('group-name-desc')}}</small>
            <Textarea
                id="description"
                v-model="groupToCreate.description"
                name="description"
                :label="$t('group-desc')"
                :placeholder="$t('description')" />
            <small class="form-text text-muted">{{$t('group-description-desc')}}</small>
            <div class="form-group">
                <input
                    id="public-checkbox"
                    v-model="groupToCreate.is_public"
                    type="checkbox"
                    name="public-checkbox" />
                <label for="public-checkbox" class="option-label">{{$t('group-public-checkbox')}}</label>
                <small class="form-text text-muted">{{$t('group-public-checkbox-desc')}}</small>
                <BaseFormError name="public-checkbox" /> 
            </div>
            <button type="submit" class="block">{{$t('create-group')}}</button>
            <button type="button" class="block" @click="close">{{$t('cancel')}}</button>
        </form>
    </div>
</template>

<script setup>
import {reactive} from 'vue';
import {useGroupStore} from '/js/store/groupStore.js';
const groupStore = useGroupStore();

const emit = defineEmits([
    'reloadGroups',
    'close',
]);

const initialData = {
    name: '',
    description: '',
    is_public: false,
}

const groupToCreate = reactive({...initialData});

async function createGroup() {
    await groupStore.createGroup(groupToCreate);
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
