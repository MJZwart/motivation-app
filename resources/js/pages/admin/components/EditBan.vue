<template>
    <div v-if="userBanToEdit">
        <Input
            id="days"
            v-model="userBanToEdit.days"
            type="number"
            name="days"
            :label="$t('days')"
            :placeholder="$t('days')"
            :disabled="userBanToEdit.end_ban"
            min="1" />
        <p>
            <Tooltip :text="$t('reset-days')">
                <FaIcon 
                    icon="repeat"
                    class="icon small"
                    @click="resetDays" />
            </Tooltip>
            {{$t('banned-until')}}: {{bannedUntil}}
        </p>
        <span v-if="bannedUntilInPast" class="invalid-feedback">
            {{$t('automatically-end-ban-warning')}}
        </span>

        <div class="form-group">
            <input 
                id="end-ban"
                v-model="userBanToEdit.end_ban" 
                name="end-ban"
                type="checkbox" />
            <label for="end-ban">{{$t('end-ban')}}</label>
            <BaseFormError name="end-ban" />
        </div>

        <Textarea
            id="comment"
            v-model="userBanToEdit.comment"
            :rows="2"
            name="comment"
            :label="$t('comment')"
            :placeholder="$t('comment')" />

        <div class="d-flex">
            <button :disabled="bannedUntilInPast" @click="confirm">{{ $t('submit') }}</button>
            <button class="ml-auto button-red" @click="emit('close')">{{ $t('cancel')}}</button>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';
import {DateTime} from 'luxon';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

const props = defineProps({
    userBan: {
        type: Object,
        required: true,
    },
});
const emit = defineEmits(['close']);

onMounted(() => {
    userBanToEdit.value = Object.assign({}, props.userBan);
    userBanToEdit.value.end_ban = false;
});

const userBanToEdit = ref(null);
const bannedUntil = computed(() => {
    let date = DateTime.fromSQL(props.userBan.created_at);
    return date.plus({days: userBanToEdit.value.days}).toLocaleString(DateTime.DATETIME_MED);
});

const bannedUntilInPast = computed(() => 
    DateTime.now() > DateTime.fromFormat(bannedUntil.value, 'dd MMM yyyy, HH:mm') 
    && userBanToEdit.value 
    && !userBanToEdit.value.end_ban);

function resetDays() {
    userBanToEdit.value.days = props.userBan.days;
}

async function confirm() {
    if (bannedUntilInPast.value) return;
    let log = DateTime.now().toLocaleString(DateTime.DATETIME_MED) + ': ';
    if (userBanToEdit.value.end_ban)
        log = log.concat('User has been unbanned. ');
    else if (userBanToEdit.value.days != props.userBan.days)
        log = log.concat(`Changed days from ${props.userBan.days} to ${userBanToEdit.value.days}. `);
    userBanToEdit.value.log = log;
    await adminStore.editBan(userBanToEdit.value);
    emit('close');
}
</script>