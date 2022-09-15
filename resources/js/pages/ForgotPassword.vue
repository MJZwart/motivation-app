<template>
    <div class="w-40-flex center">
        <h2>{{ $t('forgot-password') }}</h2>
        <form @submit.prevent="sendPasswordReset">
            <SimpleInput
                id="email"
                v-model="email.email"
                name="email"
                type="email"
                :label="$t('email')"
                :placeholder="$t('email')"
            />
            <button type="submit" class="block">{{ $t('send-password-reset') }}</button>
            <BaseFormError name="email" />
        </form>
        <div v-if="resetLinkSent" class="success-text">
            {{ $t('password-reset-link-sent') }}
        </div>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {useUserStore} from '../store/userStore';

const userStore = useUserStore();

const email = ref({
    email: '',
});
const resetLinkSent = ref(false);

async function sendPasswordReset() {
    await userStore.resetPassword(email.value);
    resetLinkSent.value = true;

}
</script>