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
            <button class="register-button center mt-3">
                <router-link to="/register" class="register-button-text">{{ $t('create-account-today') }}</router-link>
            </button>
            <h3 class="center mt-3">- {{ $t('or') }} -</h3>
            <button class="register-button center mt-3">
                <router-link to="/guest-account"  class="register-button-text">{{ $t('create-guest-account') }}</router-link>
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import TaskList from './DummyTaskList.vue';
import Character from '/js/pages/dashboard/components/reward/RewardCard.vue';
import {dummyCharacterRef, dummyTaskListRef} from './homepageService';
import {computed} from 'vue';
import {useI18n} from 'vue-i18n';
const {t} = useI18n(); // use as global scope

const dummyList = dummyTaskListRef;
const dummyCharacter = dummyCharacterRef;

const appTitle = computed(() => t('home-welcome-to', [t('app-name')]));
const appLead = computed(() => t('home-introduction'));
</script>

<style lang="scss" scoped>
.register-button {
    font-size: 2rem;
    padding: 0.8rem;
    border-radius: 0.5rem;
    background-color: var(--green);
    min-width: 50%;
    transition: all 0.3s 0s ease-in-out;
    border: none;
    .register-button-text {
        color: var(--primary)-as-text;
        text-decoration: none;
        font-family: var(--border-color);
    }
}
.register-button:hover {
    background-color: var(--dark-green);
    .register-button-text {
        color: var(--primary)-text;
    }
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
