<template>
    <div class="w-40-flex center">
        <h2>{{ $t('register') }}</h2>
        <form @submit.prevent="submitRegister">
            <Input 
                id="username"  
                v-model="register.username"
                type="text" 
                name="username" 
                :label="$t('username')"
                :placeholder="$t('username')" />
            <Input 
                id="email" 
                v-model="register.email"
                type="text" 
                name="email" 
                :label="$t('email')"
                :placeholder="$t('email')"  />
            <Input 
                id="password"  
                v-model="register.password"
                type="password" 
                name="password" 
                :label="$t('password')"
                :placeholder="$t('password')" />
            <Input 
                id="password_confirmation" 
                v-model="register.password_confirmation" 
                type="password" 
                name="password_confirmation" 
                :label="$t('repeat-password')"
                :placeholder="$t('repeat-password')" />
            <button type="submit" block>{{ $t('register-new-account') }}</button>
        </form> 
    </div>
</template>


<script setup>
import {reactive} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {useMainStore} from '/js/store/store';

const mainStore = useMainStore();
const userStore = useUserStore();

const register = reactive({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',

});
function submitRegister() {
    mainStore.clearErrors();
    userStore.register(register);
}
</script>