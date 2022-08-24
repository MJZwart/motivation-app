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
            <div class="form-group">
                <input
                    id="agree_to_tos"
                    v-model="register.agree_to_tos"
                    type="checkbox"
                    name="agree_to_tos" />
                <label for="agree_to_tos">
                    {{$t('agree-to-tos-pre')}}
                    <router-link to="/tos" target="_blank">{{$t('tos')}}</router-link>
                </label>
                <BaseFormError name="agree_to_tos" />
            </div>
            <button type="submit" block>{{ $t('register-new-account') }}</button>
        </form> 
    </div>
</template>


<script setup lang="ts">
import {reactive} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {useMainStore} from '/js/store/store';
import {Register} from 'resources/types/user';

const mainStore = useMainStore();
const userStore = useUserStore();

const register = reactive<Register>({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    agree_to_tos: false,
});
function submitRegister() {
    mainStore.clearErrors();
    userStore.register(register);
}
</script>