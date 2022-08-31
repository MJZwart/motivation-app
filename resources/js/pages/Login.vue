<template>
    <div class="w-40-flex center">
        <h2>{{ $t('login') }}</h2>
        <p v-if="info" class="invalid-feedback">{{info.error}}</p>
        <form @submit.prevent="submitLogin">
            <SimpleInput 
                id="username" 
                v-model="login.username" 
                name="username"
                type="text" 
                :label="$t('username')"
                :placeholder="$t('username')" />
            <SimpleInput  
                id="password" 
                v-model="login.password"
                type="password" 
                name="password" 
                :label="$t('password')"
                :placeholder="$t('password')" />
            <button type="submit" class="block">{{ $t('login') }}</button>
            <BaseFormError name="error" /> 
        </form> 
    </div>
</template>


<script setup lang="ts">
import type {Message} from 'resources/types/error';
import {useUserStore} from '/js/store/userStore';
import {reactive, ref} from 'vue';

const login = reactive({
    username: '',
    password: '',
});

const userStore = useUserStore();
const info = ref<Message | null>(null);

async function submitLogin() {
    info.value = await userStore.login(login);
}
</script>