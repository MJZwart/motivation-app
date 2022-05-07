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
            <Textarea
                id="feedback" 
                v-model="feedback.text" 
                :rows=4
                :label="$t('feedback')"
                name="feedback" />
            <Input
                v-if="!auth"
                id="email" 
                v-model="feedback.email" 
                name="email"
                :label="$t('email')" />
            <button type="submit">Send feedback</button>
        </form>
    </div>
</template>

<script setup>
import {reactive, computed} from 'vue';
import {FEEDBACK_TYPES} from '/js/constants/feedbackConstants.js';
import {useMainStore} from '/js/store/store';
import {useUserStore} from '/js/store/userStore';
const mainStore = useMainStore();
const userStore = useUserStore();

const feedback = reactive({
    type: 'FEEDBACK',
});
const feedbackTypes = FEEDBACK_TYPES;

const user = computed(() => userStore.user);
const auth = computed(() => userStore.authenticated);

async function sendFeedback() {
    if (user.value) {
        feedback.user_id = user.value.id;
    }
    mainStore.sendFeedback(feedback);
}
</script>