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
            <div class="form-group">
                <input 
                    id="indefinite" 
                    v-model="suspension.indefinite"
                    type="checkbox" 
                    name="indefinite" 
                    :label="$t('indefinite')"
                    :placeholder="$t('indefinite')" />
                <label for="indefinite">{{ $t('indefinite') }}</label>
                <BaseFormError name="indefinite" />
            </div>
            <div class="form-group">
                <input
                    id="close-reports"
                    v-model="suspension.close_reports"
                    type="checkbox"
                    name="close-reports"
                    :label="$t('suspend-user-close-reports')"
                    :placeholder="$t('suspend-user-close-reports')" />
                <label for="close-reports">{{ $t('suspend-user-close-reports') }}</label>
                <BaseFormError name="close-reports" />
            </div>
            <button type="submit" class="block">{{ $t('suspend-user') }}</button>
            <button type="button" class="block" @click="emit('close')">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {NewSuspension} from 'resources/types/user';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

const props = defineProps({
    userId: {
        type: Number,
        required: true,
    },
});
const emit = defineEmits(['close']);

const suspension = ref<NewSuspension>({
    reason: '',
    days: 0,
    indefinite: false,
    close_reports: true,
});

async function suspendUser() {
    if (suspension.value.indefinite) suspension.value.days = null;
    await adminStore.suspendUser(props.userId, suspension.value);
    emit('close', true);
}

</script>