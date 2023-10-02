<template>
    <AuthBase>
        <h2>{{ $t('login') }}</h2>
        <form @submit.prevent="submitLogin">
            <SimpleInput
                id="username"
                v-model="login.username"
                name="username"
                type="text"
                :label="$t('username')"
                :placeholder="$t('username')"
            />
            <SimpleInput
                id="password"
                v-model="login.password"
                type="password"
                name="password"
                :label="$t('password')"
                :placeholder="$t('password')"
            />
            <SubmitButton id="login-button" class="block">{{ $t('login') }}</SubmitButton>
            <BaseFormError name="error" />
        </form>
        <span class="d-flex">
            <router-link class="ml-auto mt-1 clear-link" to="/forgot-password">{{ $t('forgot-password-link') }}</router-link>
        </span>
        
        <hr />
        
        <div class="d-flex flex-col">
            {{ $t('no-account-prompt') }}
            <router-link to="/register" class="text-decoration-none">
                <button class="block center mt-3">
                    {{ $t('register') }}
                </button>
            </router-link>
            <h3 class="center mt-3">- {{ $t('or') }} -</h3>
            <router-link v-if="!guestAccount" to="/guest-account"  class="text-decoration-none">
                <button class="block center mt-3">
                    {{ $t('create-guest-account') }}
                </button>
            </router-link>
            <button v-else class="block center mt-3" @click="continueGuestAccount()">
                {{ $t('continue-guest-account') }}
            </button>
        </div>
    </AuthBase>
</template>

<script setup lang="ts">
import {useUserStore} from '/js/store/userStore';
import {onMounted, ref} from 'vue';
import AuthBase from './components/AuthBase.vue';

const login = ref({
    username: '',
    password: '',
});

const userStore = useUserStore();

async function submitLogin() {
    await userStore.login(login.value);
}

const guestAccount = ref(false);

onMounted(() => {
    const localToken = localStorage.getItem('guestToken');
    guestAccount.value = !!localToken;
});

function continueGuestAccount() {
    if (!guestAccount.value) return;

    userStore.continueGuestAccount();
}
</script>
