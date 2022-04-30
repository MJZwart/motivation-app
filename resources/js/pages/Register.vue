<template>
    <div class="w-40 center">
        <h2>{{ $t('register') }}</h2>
        <form @submit.prevent="submitRegister">
            <div class="form-group">
                <label for="username">{{$t('username')}}</label>
                <input 
                    id="username"  
                    v-model="register.username"
                    type="text" 
                    name="username" 
                    :placeholder="$t('username')" />
                <base-form-error name="username" /> 
            </div>
            <div class="form-group">
                <label for="email">{{$t('email')}}</label>
                <input 
                    id="email" 
                    v-model="register.email"
                    type="text" 
                    name="email" 
                    :placeholder="$t('email')"  />
                <base-form-error name="email" /> 
            </div>
            <div class="form-group">
                <label for="password">{{$t('password')}}</label>
                <input 
                    id="password"  
                    v-model="register.password"
                    type="password" 
                    name="password" 
                    :placeholder="$t('password')" />
                <base-form-error name="password" /> 
            </div>
            <div class="form-group">
                <label for="password_confirmation">{{$t('repeat-password')}}</label>
                <input 
                    id="password_confirmation" 
                    v-model="register.password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    :placeholder="$t('repeat-password')" />
                <base-form-error name="password_confirmation" /> 
            </div>
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