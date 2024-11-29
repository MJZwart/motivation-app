<template>
    <AuthBase class="d-flex flex-col">
        <h2>{{ $t('guest-account') }}</h2>
        <span>{{ $t('which-reward-type') }}</span>
        <div class="choice mt-3 mb-3">
            <button 
                class="long select-button" 
                :class="{ active: chosenReward === 1 }" 
                @click="selectRewardType(1)">
                {{$t('village')}}
            </button>
        
            <button 
                class="long select-button" 
                :class="{ active: chosenReward === 0 }" 
                @click="selectRewardType(0)">
                {{ $t('no-rewards') }}
            </button>
        </div>
        <SubmitButton :disabled="chosenReward === null" class="ml-auto" @click="createGuestAccount">
            {{ $t('create-guest-account') }}
        </SubmitButton>
    </AuthBase>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import AuthBase from './components/AuthBase.vue';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import axios from 'axios';
import {setUser} from '/js/services/userService';
import router from '/js/router/router';

const chosenReward = ref<0 | 1 | null>();
function selectRewardType(rewards: 0 | 1) {
    chosenReward.value = rewards;
}

async function createGuestAccount() {
    if (!chosenReward.value) return;
    const {data} = await axios.post('/guest-account', {'reward': chosenReward.value});
    localStorage.setItem('guestToken',
        JSON.stringify(data.data.loginToken));
    setUser(data.data.user);
    router.push('/dashboard')
}
</script>

<style lang="scss" scoped>
.long {
    width: 100%;
    padding: 0.75rem;
}
.large {
    width: 100%;
    padding: 2rem;
}
.select-button {
    background-color: var(--primary);
    border: none;
    box-shadow: var(--basic-shadow);
}
.select-button.active {
    background-color: var(--secondary);
    box-shadow: var(--basic-shadow-inset);
}
</style>