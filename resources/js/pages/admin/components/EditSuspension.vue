<template>
    <div>
        <SimpleInput
            id="days"
            v-model="suspendedUser.days"
            type="number"
            name="days"
            :label="$t('days')"
            :placeholder="$t('days')"
            :disabled="suspendedUser.end_suspension"
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
            v-model="suspendedUser.end_suspension" 
            name="end-suspension" 
            :label="$t('end-suspension')" />

        <SimpleTextarea
            id="comment"
            v-model="suspendedUser.suspension_edit_comment"
            :rows="2"
            name="comment"
            :label="$t('comment')"
            :placeholder="$t('comment')"
        />
        <FormControls
            :disabled="suspendedUntilInPast"
            @submit="confirm"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, ref} from 'vue';
import {DateTime} from 'luxon';
import {SuspendedUser} from 'resources/types/user.js';
import {getDateWithAddedDays} from '/js/services/dateService';
import {RESET} from '/js/constants/iconConstants';
import {deepCopy} from '/js/helpers/copy';
import FormControls from '/js/components/global/FormControls.vue';

const props = defineProps<{form: SuspendedUser}>();
const emit = defineEmits(['close', 'submit']);

const suspendedUser = ref(deepCopy(props.form));

onMounted(() => {
    suspendedUser.value.suspension_edit_comment = '';
});

const suspendedUntil = computed(() => {
    if (!suspendedUser.value) return '';
    return getDateWithAddedDays(props.form.created_at, suspendedUser.value.days);
});

const suspendedUntilInPast = computed(() =>
    Boolean(
        DateTime.now() > DateTime.fromFormat(suspendedUntil.value.toString(), 'dd MMM yyyy, HH:mm') &&
            suspendedUser.value &&
            !suspendedUser.value.end_suspension,
    ),
);

function resetDays() {
    if (suspendedUser.value) suspendedUser.value.days = props.form.days;
}

async function confirm() {
    if (suspendedUntilInPast.value || !suspendedUser.value) return;
    let changesText = '';
    if (suspendedUser.value.end_suspension) changesText = 'User has been unsuspended.';
    else if (suspendedUser.value.days != props.form.days)
        changesText = `Changed days from ${props.form.days} to ${suspendedUser.value.days}.`;
    suspendedUser.value.suspension_edit_log = parseLogIntoJson(
        changesText, 
        suspendedUser.value.suspension_edit_comment,
        props.form.suspension_edit_log, 
    );
    emit('submit', suspendedUser.value);
}
function parseLogIntoJson(changes: string, comment: string, log: string) {
    const timeStamp = DateTime.now().toLocaleString(DateTime.DATETIME_MED);
    let parsedLog = [];
    if (log) parsedLog = JSON.parse(log);
    parsedLog.push({date: timeStamp, log: changes, comment: comment});
    return JSON.stringify(parsedLog);
}
</script>
