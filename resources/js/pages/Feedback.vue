<template>
    <div class="w-50 center">
        <h2>{{ $t('feedback') }}</h2>
        <p>{{ $t('feedback-explanation') }}</p>
        <b>{{ $t('feedback-features-header') }}</b>
        <p>{{ $t('feedback-features') }}</p>
        <b>{{ $t('feedback-accessibility-header') }}</b>
        <p>{{ $t('feedback-accessibility') }}</p>
        <b-form @submit.prevent="sendFeedback">
            <b-form-group
                :label="$t('type')"
                label-for="type" >
                <b-form-select
                    id="type" 
                    v-model="feedback.type" 
                    name="type"
                    :options="feedbackTypes" />
                <BaseFormError name="type" /> 
            </b-form-group>
            <b-form-group
                :label="$t('feedback')"
                label-for="feedback">
                <b-form-textarea
                    id="feedback" 
                    v-model="feedback.text" 
                    rows="4"
                    name="feedback" />
                <BaseFormError name="feedback" /> 
            </b-form-group>
            <b-form-group
                v-if="!auth"
                :label="$t('email')"
                label-for="email">
                <b-form-input
                    id="email" 
                    v-model="feedback.email" 
                    name="email" />
                <BaseFormError name="email" /> 
            </b-form-group>
            <b-button type="submit">Send feedback</b-button>
        </b-form>
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
            auth: 'user/authenticated',
        }),
    },
    mounted() {
        // console.log(auth);
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