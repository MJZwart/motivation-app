<template>
    <AuthBase>
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
            <SubmitButton class="block">{{ $t('send-password-reset') }}</SubmitButton>
        </form>
        <div v-if="resetLinkSent" class="success-text">
            {{ $t('password-reset-link-sent') }}
        </div>
    </AuthBase>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
import AuthBase from './components/AuthBase.vue';

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