<template>
    <div v-if="userSuspensionToEdit">
        <SimpleInput
            id="days"
            v-model="userSuspensionToEdit.days"
            type="number"
            name="days"
            :label="$t('days')"
            :placeholder="$t('days')"
            :disabled="userSuspensionToEdit.end_suspension"
            min="1"
        />
        <p>
            <Tooltip :text="$t('reset-days')" placement="right">
                <Icon :icon="RESET" class="icon" @click="resetDays" />
            </Tooltip>
            {{ $t('suspended-until') }}: {{ suspendedUntil }}
        </p>
        <span v-if="suspendedUntilInPast" class="invalid-feedback">
            {{ $t('automatically-end-suspension-warning') }}
        </span>

        <SimpleFormCheckbox 
            id="end-suspension" 
            v-model="userSuspensionToEdit.end_suspension" 
            name="end-suspension" 
            :label="$t('end-suspension')" />

        <SimpleTextarea
            id="comment"
            v-model="userSuspensionToEdit.suspension_edit_comment"
            :rows="2"
            name="comment"
            :label="$t('comment')"
            :placeholder="$t('comment')"
        />

        <SubmitButton :disabled="suspendedUntilInPast" class="block" @click="confirm">{{ $t('submit') }}</SubmitButton>
        <button class="block button-cancel" @click="emit('close')">{{ $t('cancel') }}</button>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, ref} from 'vue';
import {DateTime} from 'luxon';
import {useAdminStore} from '/js/store/adminStore';
import {SuspendedUser} from 'resources/types/user.js';
import {getDateWithAddedDays} from '/js/services/dateService';
import {RESET} from '/js/constants/iconConstants';
const adminStore = useAdminStore();

const props = defineProps<{userSuspension: SuspendedUser}>();
const emit = defineEmits(['close']);

const userSuspensionToEdit = ref<SuspendedUser>(Object.assign({}, props.userSuspension));

onMounted(() => {
    userSuspensionToEdit.value.suspension_edit_comment = '';
});

const suspendedUntil = computed(() => {
    if (!userSuspensionToEdit.value) return '';
    return getDateWithAddedDays(props.userSuspension.created_at, userSuspensionToEdit.value.days);
});

const suspendedUntilInPast = computed(() =>
    Boolean(
        DateTime.now() > DateTime.fromFormat(suspendedUntil.value.toString(), 'dd MMM yyyy, HH:mm') &&
            userSuspensionToEdit.value &&
            !userSuspensionToEdit.value.end_suspension,
    ),
);

function resetDays() {
    if (userSuspensionToEdit.value) userSuspensionToEdit.value.days = props.userSuspension.days;
}

async function confirm() {
    if (suspendedUntilInPast.value || !userSuspensionToEdit.value) return;
    let changesText = '';
    if (userSuspensionToEdit.value.end_suspension) changesText = 'User has been unsuspended.';
    else if (userSuspensionToEdit.value.days != props.userSuspension.days)
        changesText = `Changed days from ${props.userSuspension.days} to ${userSuspensionToEdit.value.days}.`;
    userSuspensionToEdit.value.suspension_edit_log = parseLogIntoJson(
        changesText, 
        userSuspensionToEdit.value.suspension_edit_comment,
        props.userSuspension.suspension_edit_log, 
    );
    await adminStore.editSuspension(userSuspensionToEdit.value);
    emit('close');
}
function parseLogIntoJson(changes: string, comment: string, log: string) {
    const timeStamp = DateTime.now().toLocaleString(DateTime.DATETIME_MED);
    let parsedLog = [];
    if (log) parsedLog = JSON.parse(log);
    parsedLog.push({date: timeStamp, log: changes, comment: comment});
    return JSON.stringify(parsedLog);
}
</script>
