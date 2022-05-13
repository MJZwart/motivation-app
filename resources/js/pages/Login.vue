<template>
    <div class="w-40-flex center">
        <h2>{{ $t('login') }}</h2>
        <p v-if="info" class="invalid-feedback">{{info.error}}</p>
        <form @submit.prevent="submitLogin">
            <Input 
                id="username" 
                v-model="login.username" 
                name="username"
                type="text" 
                :label="$t('username')"
                :placeholder="$t('username')" />
            <Input  
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


<script setup>
import {useUserStore} from '/js/store/userStore';
import {reactive, ref} from 'vue';

const login = reactive({
    username: '',
    password: '',
});

const userStore = useUserStore();
const info = ref(null);

async function submitLogin() {
    info.value = await userStore.login(login);
}
</script>