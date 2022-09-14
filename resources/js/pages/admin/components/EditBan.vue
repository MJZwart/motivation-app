<template>
    <div v-if="userBanToEdit">
        <SimpleInput
            id="days"
            v-model="userBanToEdit.days"
            type="number"
            name="days"
            :label="$t('days')"
            :placeholder="$t('days')"
            :disabled="userBanToEdit.end_ban"
            min="1"
        />
        <p>
            <Tooltip :text="$t('reset-days')" placement="right">
                <FaIcon icon="repeat" class="icon small" @click="resetDays" />
            </Tooltip>
            {{ $t('banned-until') }}: {{ bannedUntil }}
        </p>
        <span v-if="bannedUntilInPast" class="invalid-feedback">
            {{ $t('automatically-end-ban-warning') }}
        </span>

        <div class="form-group">
            <input id="end-ban" v-model="userBanToEdit.end_ban" name="end-ban" type="checkbox" />
            <label for="end-ban">{{ $t('end-ban') }}</label>
            <BaseFormError name="end-ban" />
        </div>

        <SimpleTextarea
            id="comment"
            v-model="userBanToEdit.ban_edit_comment"
            :rows="2"
            name="comment"
            :label="$t('comment')"
            :placeholder="$t('comment')"
        />

        <div class="d-flex">
            <button :disabled="bannedUntilInPast" @click="confirm">{{ $t('submit') }}</button>
            <button class="ml-auto button-red" @click="emit('close')">{{ $t('cancel') }}</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, ref} from 'vue';
import {DateTime} from 'luxon';
import {useAdminStore} from '/js/store/adminStore';
import {BannedUser} from 'resources/types/user.js';
import {getDateWithAddedDays} from '/js/services/dateService';
const adminStore = useAdminStore();

const props = defineProps<{userBan: BannedUser}>();
const emit = defineEmits(['close']);

const userBanToEdit = ref<BannedUser>(Object.assign({}, props.userBan));
const bannedUntil = computed(() => {
    if (!userBanToEdit.value) return '';
    return getDateWithAddedDays(props.userBan.created_at, userBanToEdit.value.days);
});

const bannedUntilInPast = computed(() =>
    Boolean(
        DateTime.now() > DateTime.fromFormat(bannedUntil.value.toString(), 'dd MMM yyyy, HH:mm') &&
            userBanToEdit.value &&
            !userBanToEdit.value.end_ban,
    ),
);

function resetDays() {
    if (userBanToEdit.value) userBanToEdit.value.days = props.userBan.days;
}

async function confirm() {
    if (bannedUntilInPast.value || !userBanToEdit.value) return;
    let log = DateTime.now().toLocaleString(DateTime.DATETIME_MED) + ': ';
    if (userBanToEdit.value.end_ban) log = log.concat('User has been unbanned. ');
    else if (userBanToEdit.value.days != props.userBan.days)
        log = log.concat(`Changed days from ${props.userBan.days} to ${userBanToEdit.value.days}. `);
    userBanToEdit.value.ban_edit_log = log;
    await adminStore.editBan(userBanToEdit.value);
    emit('close');
}
</script>
