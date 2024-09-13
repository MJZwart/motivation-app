<template>
    <AuthBase>
        <h2>{{ $t('reset-password') }}</h2>
        <form @submit.prevent="sendResetPassword">
            <SimpleInput
                id="email"
                v-model="resetPassword.email"
                name="email"
                type="email"
                :label="$t('email')"
                :placeholder="$t('email')"
                disabled
            />
            <SimpleInput 
                id="password"  
                v-model="resetPassword.password"
                type="password" 
                name="password" 
                :label="$t('password')"
                :placeholder="$t('password')" />
            <SimpleInput 
                id="password_confirmation" 
                v-model="resetPassword.password_confirmation" 
                type="password" 
                name="password_confirmation" 
                :label="$t('repeat-password')"
                :placeholder="$t('repeat-password')" />
            <SubmitButton class="block">{{ $t('reset-password') }}</SubmitButton>
        </form>
    </AuthBase>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {useRoute, useRouter} from 'vue-router';
import AuthBase from './components/AuthBase.vue';
import axios from 'axios';

const route = useRoute();
const userStore = useUserStore();
const router = useRouter();

const resetPassword = ref({
    email: '',
    password: '',
    password_confirmation: '',
    token: '',
});

onMounted(() => {
    const {token, email} = route.query;
    if (token && email) {
        resetPassword.value.email = email.toString();
        resetPassword.value.token = token.toString();
    }
});

async function sendResetPassword() {
    await axios.post('/password/reset', resetPassword.value);
    router.push('/login');
}
</script>