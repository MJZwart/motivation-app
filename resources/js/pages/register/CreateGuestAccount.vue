<template>
    <AuthBase class="d-flex flex-col">
        <h2>{{ $t('guest-account') }}</h2>
        <span>{{ $t('which-reward-type') }}</span>
        <div class="choice mt-3 mb-3">
            <button 
                class="large select-button" 
                :class="{active: chosenReward === 'CHARACTER'}" 
                @click="selectRewardType('CHARACTER')">
                {{$t('character')}}
            </button>

            <button 
                class="large select-button" 
                :class="{ active: chosenReward === 'VILLAGE' }" 
                @click="selectRewardType('VILLAGE')">
                {{$t('village')}}
            </button>
        
            <button 
                class="long select-button" 
                :class="{ active: chosenReward === 'NONE' }" 
                @click="selectRewardType('NONE')">
                {{ $t('no-rewards') }}
            </button>
        </div>
        <SubmitButton :disabled="chosenReward === ''" class="ml-auto" @click="createGuestAccount">
            {{ $t('create-guest-account') }}
        </SubmitButton>
    </AuthBase>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import AuthBase from './components/AuthBase.vue';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {useUserStore} from '/js/store/userStore';

const userStore = useUserStore();

const chosenReward = ref('');
function selectRewardType(rewards: string) {
    chosenReward.value = rewards;
}

function createGuestAccount() {
    userStore.createGuestAccount({'reward': chosenReward.value})
}
</script>

<style lang="scss" scoped>
.long {
    width: 100%;
    padding: 0.75rem;
    grid-area: b;
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
.choice {
    display:grid;
    gap: 1rem;
    grid-template-areas: 
        ". ."
        "b b";
}
</style>