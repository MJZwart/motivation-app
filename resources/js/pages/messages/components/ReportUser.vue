<template>
    <div>
        <h5>{{ reportTitle }}</h5>
        <div class="form-group">
            <label for="reason">{{ $t('report-reason') }}</label>
            <select id="reason" v-model="report.reason" name="reason">
                <option v-for="(option, index) in reportReasons" :key="index" :value="option.value">
                    {{ $t(option.text) }}
                </option>
            </select>
            <BaseFormError name="reason" />
        </div>
        <SimpleTextarea
            id="comment"
            v-model="report.comment"
            name="comment"
            :label="$t('report-comment')"
            :placeholder="$t('comment')"
        />    
        <FormControls
            :submit-text="$t('report-user')"
            @submit="reportUser"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import {computed, ref} from 'vue';
import {REPORT_REASONS} from '/js/constants/reportUserConstants';
import {useI18n} from 'vue-i18n';
import type {StrippedUser} from 'resources/types/user';
import type {NewReportedUser} from 'resources/types/admin';
import FormControls from '/js/components/global/FormControls.vue';
import axios from 'axios';
const {t} = useI18n();

const emit = defineEmits(['close']);
const props = defineProps<{
    user: StrippedUser;
    conversation_id?: string;
    group_id?: number;
}>();

const reportTitle = computed(() => t('report-user-name', {user: props.user.username}));

const reportReasons = REPORT_REASONS;
const report = ref<NewReportedUser>({reason: '', comment: ''});

async function reportUser() {
    if (props.conversation_id) report.value.conversation_id = props.conversation_id;
    if (props.group_id) report.value.group_id = props.group_id;
    await axios.post('/user/' + props.user.id + '/report', report.value);
    emit('close');
}
</script>
