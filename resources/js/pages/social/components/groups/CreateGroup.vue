<template>
    <div>
        <SimpleInput
            id="name"
            v-model="group.name"
            name="name"
            :label="$t('name')"
            :placeholder="$t('name')"
        />
        <small class="form-text text-muted">{{ $t('group-name-desc') }}</small>

        <SimpleTextarea
            id="description"
            v-model="group.description"
            name="description"
            :label="$t('group-desc')"
            :placeholder="$t('description')"
        />
        <small class="form-text text-muted mb-3">{{ $t('group-description-desc') }}</small>

        <SimpleFormCheckbox 
            id="public-checkbox" 
            v-model="group.is_public" 
            name="is_public" 
            class="mb-0"
            :label="$t('group-public-checkbox')" />
        <small class="form-text text-muted mb-3">{{ $t('group-public-checkbox-desc') }}</small>
            
        <SimpleFormCheckbox 
            v-if="group.is_public"
            id="require-application-checkbox" 
            v-model="group.require_application" 
            name="require_application" 
            class="mb-0"
            :label="$t('group-require-application-checkbox')" />
        <small class="form-text text-muted mb-3">{{ $t('group-require-application-checkbox-desc') }}</small>
                
        <FormControls
            :submit-text="$t('create-group')"
            @submit="$emit('submit', group)"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import type {NewGroup} from 'resources/types/group';
import {ref} from 'vue';
import FormControls from '/js/components/global/FormControls.vue';

defineEmits(['submit', 'close']);

const props = defineProps<{form: NewGroup}>();

const group = ref<NewGroup>({...props.form});
</script>
