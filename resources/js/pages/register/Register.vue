<template>
    <AuthBase>
        <h2>{{ $t('register') }}</h2>
        <form @submit.prevent="submitRegister">
            <SimpleInput 
                id="username"  
                v-model="register.username"
                type="text" 
                name="username" 
                :label="$t('username')"
                :placeholder="$t('username')" />
            <SimpleInput 
                id="email" 
                v-model="register.email"
                type="text" 
                name="email" 
                :label="$t('email')"
                :placeholder="$t('email')"  />
            <SimpleInput 
                id="password"  
                v-model="register.password"
                type="password" 
                name="password" 
                :label="$t('password')"
                :placeholder="$t('password')" />
            <SimpleInput 
                id="password_confirmation" 
                v-model="register.password_confirmation" 
                type="password" 
                name="password_confirmation" 
                :label="$t('repeat-password')"
                :placeholder="$t('repeat-password')" />
            <div class="form-group">
                <SimpleCheckbox 
                    id="agree_to_tos" 
                    v-model="register.agree_to_tos" 
                    name="agree_to_tos" />
                <label for="agree_to_tos" class="pointer" @click="register.agree_to_tos = !register.agree_to_tos">
                    {{$t('agree-to-tos-pre')}}
                    <router-link to="/tos" target="_blank">{{$t('tos')}}</router-link>
                </label>
                <BaseFormError name="agree_to_tos" />
            </div>
            <SubmitButton block>{{ $t('register-new-account') }}</SubmitButton>
        </form> 
    </AuthBase>
</template>


<script setup lang="ts">
import {reactive} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {Register} from 'resources/types/user';
import {currentLang} from '/js/services/languageService';
import {clearErrors} from '/js/services/errorService';
import AuthBase from './components/AuthBase.vue';

const userStore = useUserStore();

const register = reactive<Register>({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    agree_to_tos: false,
    language: currentLang.value,
});
function submitRegister() {
    clearErrors();
    userStore.register(register);
}
</script>