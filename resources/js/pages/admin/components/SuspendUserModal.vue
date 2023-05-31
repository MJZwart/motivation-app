<template>
    <div>
        <form @submit.prevent="suspendUser">
            <SimpleTextarea 
                id="reason" 
                v-model="suspension.reason"
                :rows="2"
                name="reason" 
                :label="$t('reason')"
                :placeholder="$t('reason')"  />
            <SimpleInput 
                id="days" 
                v-model="suspension.days"
                type="number" 
                name="days" 
                :label="$t('days')"
                :placeholder="$t('days')"  />
            <SimpleFormCheckbox 
                id="indefinite"
                v-model="suspension.indefinite" 
                :label="$t('indefinite')" 
                name="indefinite" />
            <SimpleFormCheckbox 
                id="close-reports"
                v-model="suspension.close_reports" 
                :label="$t('suspend-user-close-reports')" 
                name="close-reports" />
            <SubmitButton class="block">{{ $t('suspend-user') }}</SubmitButton>
            <button type="button" class="block button-cancel" @click="emit('close')">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {NewSuspension} from 'resources/types/user';
import {deepCopy} from '/js/helpers/copy';

const props = defineProps<{form: NewSuspension}>();
const emit = defineEmits(['close', 'submit']);

const suspension = ref<NewSuspension>(deepCopy(props.form));

async function suspendUser() {
    if (suspension.value.indefinite) suspension.value.days = null;
    else if (suspension.value.days) suspension.value.days = parseInt(suspension.value.days.toString(), 10);
    emit('submit', suspension.value);
}
</script>