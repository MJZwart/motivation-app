<template>
    <div>
        <h5>{{ reportTitle }}</h5>
        <form @submit.prevent="reportUser">
            <div class="form-group">
                <label for="reason">{{$t('report-reason')}}</label>
                <select
                    id="reason"
                    v-model="report.reason"
                    name="reason">
                    <option v-for="(option, index) in reportReasons" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <BaseFormError name="reason" /> 
            </div>
            <Textarea 
                id="comment" 
                v-model="report.comment"
                name="comment" 
                :label="$t('report-comment')"
                :placeholder="$t('comment')"  />
            <button type="submit" class="block">{{ $t('report-user') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';
import {REPORT_REASONS} from '/js/constants/reportUserConstants';
import {useUserStore} from '/js/store/userStore';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope
const userStore = useUserStore();

const emit = defineEmits(['close']);
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    conversation_id: {
        type: String,
        required: false,
    },
});

const reportTitle = computed(() => t('report-user-name', {user: props.user.username}));

const reportReasons = REPORT_REASONS;
const report = ref({reason: null, comment: null});

async function reportUser() {
    if (props.conversation_id) report.value.conversation_id = props.conversation_id;
    await userStore.reportUser([props.user.value, report.value])
    close();
}
function close() {
    emit('close');
}
</script>