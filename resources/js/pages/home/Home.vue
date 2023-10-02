<template>
    <div class="w-60-flex center">
        <div class="d-flex">
            <div class="w-80 center jumbotron">
                <h1 class="display-3">{{ appTitle }}</h1>
                <p class="lead">{{ appLead }}</p>
            </div>
        </div>
        <div class="card-deck text-center">
            <ContentBlock title="explanation-tasks-header">
                <TaskList :taskList="dummyList" class="task-list" />
                <p class="silent">{{ $t('changes-will-not-be-saved') }}</p>
                {{ $t('explanation-tasks') }}
            </ContentBlock>
            <ContentBlock title="explanation-reward-header">
                <Character class="dummy-character" :reward="dummyCharacter" :rewardType="'CHARACTER'" :userReward="true" />
                {{ $t('explanation-reward') }}
            </ContentBlock>
            <ContentBlock title="explanation-social-header">
                {{ $t('explanation-social') }}
            </ContentBlock>
        </div>
        <div class="d-flex flex-col">
            <router-link to="/register" class="register-button center mt-3">
                <button>
                    {{ $t('create-account-today') }}
                </button>
            </router-link>
            <h3 class="center mt-3">- {{ $t('or') }} -</h3>
            <router-link v-if="!guestAccount" to="/guest-account" class="register-button center mt-3">
                <button>
                    {{ $t('create-guest-account') }}
                </button>
            </router-link>
            <button v-else class="register-button center mt-3" @click="continueGuestAccount()">
                {{ $t('continue-guest-account')}}
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import TaskList from './DummyTaskList.vue';
import Character from '/js/pages/dashboard/components/reward/RewardCard.vue';
import {dummyCharacterRef, dummyTaskListRef} from './homepageService';
import {computed, onMounted, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
import {useI18n} from 'vue-i18n';
const {t} = useI18n(); // use as global scope;

const userStore = useUserStore();

const dummyList = dummyTaskListRef;
const dummyCharacter = dummyCharacterRef;

const appTitle = computed(() => t('home-welcome-to', [t('app-name')]));
const appLead = computed(() => t('home-introduction'));

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

<style lang="scss" scoped>
.register-button button {
    font-size: 2rem;
    padding: 0.8rem;
    border-radius: 0.5rem;
    background-color: var(--green);
    min-width: 50%;
    transition: all 0.3s 0s ease-in-out;
    border: none;
    color: var(--white);
    text-decoration: none;
    font-family: var(--border-color);
}
.register-button button:hover {
    background-color: var(--dark-green);
    border-radius: 0.5rem;
    color: var(--white);
}
.display-3 {
    font-size: 4rem;
}
.w-60-flex {
    max-width: 850px;
}
.dummy-character {
    
    box-shadow: 0 0.125rem 0.5rem rgb(0 0 0 / 75%);
    border-radius: 0.5rem;
}

@media (max-width: 400px) {
    .display-3 {
        font-size: 3rem;
    }
}
</style>
