<template>
    <div class="w-50 center">
        <h2>{{ $t('feedback') }}</h2>
        <p>{{ $t('feedback-explanation') }}</p>
        <b>{{ $t('feedback-features-header') }}</b>
        <p>{{ $t('feedback-features') }}</p>
        <b>{{ $t('feedback-accessibility-header') }}</b>
        <p>{{ $t('feedback-accessibility') }}</p>
        <form @submit.prevent="sendFeedback">
            <div class="form-group">
                <label for="type">{{$t('type')}}</label>
                <select
                    id="type" 
                    v-model="feedback.type" 
                    name="type">
                    <option v-for="(option, index) in feedbackTypes" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <BaseFormError name="type" /> 
            </div>
            <div class="form-group">
                <label for="feedback">{{$t('feedback')}}</label>
                <textarea
                    id="feedback" 
                    v-model="feedback.text" 
                    rows="4"
                    name="feedback" />
                <BaseFormError name="feedback" /> 
            </div>
            <div v-if="!user" class="form-group">
                <label for="email">{{$t('email')}}</label>
                <input
                    id="email" 
                    v-model="feedback.email" 
                    name="email" />
                <BaseFormError name="email" /> 
            </div>
            <button type="submit">Send feedback</button>
        </form>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import BaseFormError from '../components/BaseFormError.vue';
import {FEEDBACK_TYPES} from '../constants/feedbackConstants.js';
export default {
    components: {BaseFormError},
    data() {
        return {
            feedback: {
                type: 'FEEDBACK',
            },
            feedbackTypes: FEEDBACK_TYPES,
        }
    },
    computed: {
        ...mapGetters({
            conversations: 'message/getConversations',
            user: 'user/getUser',
        }),
    },
    methods: {
        sendFeedback() {
            if (this.user) {
                this.feedback.user_id = this.user.id;
            }
            this.$store.dispatch('sendFeedback', this.feedback);
        },
    },
}
</script>