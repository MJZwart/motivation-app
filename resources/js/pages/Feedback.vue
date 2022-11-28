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
                <label for="type">{{ $t('type') }}</label>
                <select id="type" v-model="feedback.type" name="type">
                    <option v-for="(option, index) in feedbackTypes" :key="index" :value="option.value">
                        {{ $t(option.text) }}
                    </option>
                </select>
                <BaseFormError name="type" />
            </div>
            <SimpleTextarea id="feedback" v-model="feedback.text" :rows="4" :label="$t('feedback')" name="feedback" />
            <SimpleInput v-if="!auth" id="email" v-model="feedback.email" name="email" :label="$t('email')" />
            <div class="form-group">
                <input id="diagnostics" v-model="feedback.diagnostics_approval" type="checkbox" />
                <label for="diagnostics">{{$t('send-diagnostics-information')}}</label>
                <small class="silent">{{$t('send-diagnostics-information-explanation')}}</small>
            </div>
            <button type="submit">{{ $t('send-feedback') }}</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import type {NewFeedback} from 'resources/types/feedback';
import {ref, computed} from 'vue';
import {FEEDBACK_TYPES} from '/js/constants/feedbackConstants.js';
import {useMainStore} from '/js/store/store';
import {useUserStore} from '/js/store/userStore';
import {getDiagnostics} from '/js/services/platformService';

const mainStore = useMainStore();
const userStore = useUserStore();

const emptyFeedback = {
    type: 'FEEDBACK',
    text: '',
    email: '',
    diagnostics_approval: false,
};

const feedback = ref<NewFeedback>({...emptyFeedback});
const feedbackTypes = FEEDBACK_TYPES;

const user = computed(() => userStore.user);
const auth = computed(() => userStore.authenticated);

async function sendFeedback() {
    if (feedback.value.diagnostics_approval)
        feedback.value.diagnostics = getDiagnostics();
    if (user.value) {
        feedback.value.user_id = user.value.id;
    }
    await mainStore.sendFeedback(feedback.value);
    delete feedback.value.diagnostics;
}
</script>
