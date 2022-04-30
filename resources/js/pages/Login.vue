<template>
    <div class="w-40 center">
        <h2>{{ $t('login') }}</h2>
        <form @submit.prevent="submitLogin">
            <div class="form-group">
                <label label-for="username">{{$t('username')}}</label>
                <input 
                    id="username" 
                    v-model="login.username" 
                    type="text" 
                    :placeholder="$t('username')" />
                <base-form-error name="username" /> 
            </div>
            <div class="form-group">
                <label label-for="password">{{$t('password')}}</label>
                <input  
                    id="password" 
                    v-model="login.password"
                    type="password" 
                    name="password" 
                    :placeholder="$t('password')" />
                <base-form-error name="password" /> 
            </div>
            <button type="submit" class="block">{{ $t('login') }}</button>
            <base-form-error name="error" /> 
        </form> 
    </div>
</template>


<script setup>
import {useUserStore} from '/js/store/userStore';
import {reactive} from 'vue';

const login = reactive({
    username: '',
    password: '',
});

const userStore = useUserStore();

function submitLogin() {
    userStore.login(login);
}
</script>