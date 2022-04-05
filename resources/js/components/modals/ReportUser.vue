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
                <base-form-error name="reason" /> 
            </div>
            <div class="form-group">
                <label for="comment">{{$t('report-comment')}}</label>
                <textarea 
                    id="comment" 
                    v-model="report.comment"
                    name="comment" 
                    :placeholder="$t('comment')"  />
                <base-form-error name="comment" /> 
            </div>
            <button type="submit" class="block">{{ $t('report-user') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>

<script>
import BaseFormError from '../BaseFormError.vue';
import {REPORT_REASONS} from '../../constants/reportUserConstants';
export default {
    data() {
        return {
            reportReasons: REPORT_REASONS,
            report: {reason: null, comment: null},
        }
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
        conversation_id: {
            type: String,
            required: false,
        },
    },
    components: {BaseFormError},
    methods: {
        reportUser() {
            if (this.conversation_id) this.report.conversation_id = this.conversation_id;
            this.$store.dispatch('user/reportUser', [this.user, this.report]).then(() => {
                this.close();
            });
        },
        close() {
            this.$emit('close');
        },
    },
    computed: {
        reportTitle() {
            return this.$t('report-user-name', {user: this.user.username});
        },
    },
    
}
</script>