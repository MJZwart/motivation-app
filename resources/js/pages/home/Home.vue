<template>
    <div class="w-60-flex center">
        <div class="d-flex">
            <div class="w-80 center jumbotron">
                <h1 class="display-3">{{ appTitle }}</h1>
                <p class="lead">{{ appLead }}</p>
            </div>
        </div>
        <div class="card-deck text-center">
            <div class="card home-card">
                <div class="card-header">{{ $t('explanation-tasks-header') }}</div>
                <div class="card-body">
                    <TaskList :taskList="dummyList" class="task-list" />
                    <div class="card-text">
                        <p>{{ $t('explanation-tasks') }}</p>
                    </div>
                </div>
            </div>
            <div class="card home-card">
                <div class="card-header">{{ $t('explanation-reward-header') }}</div>
                <div class="card-body">
                    <Character :reward="dummyCharacter" :rewardType="'CHARACTER'" :userReward="true" />
                    <div class="card-text">
                        <p>{{ $t('explanation-reward') }}</p>
                    </div>
                </div>
            </div>
            <div class="card home-card">
                <div class="card-header">{{ $t('explanation-social-header') }}</div>
                <div class="card-body">
                    <p>{{ $t('explanation-social') }}</p>
                </div>
            </div>
        </div>
        <!-- <div deck class="card-deck w-50 text-center center"> -->
        <!-- </div> -->
        <div class="d-flex">
            <button class="register-button center mt-3">
                <router-link to="/register" class="register-button-text">{{ $t('create-account-today') }}</router-link>
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import TaskList from './DummyTaskList.vue';
import Character from '/js/pages/dashboard/components/reward/RewardCard.vue';
import {DUMMY_TASK_LIST, DUMMY_CHARACTER} from '/js/constants/dummyConstants.js';
import {computed} from 'vue';
import {useI18n} from 'vue-i18n';
const {t} = useI18n(); // use as global scope

const dummyList = DUMMY_TASK_LIST;
const dummyCharacter = DUMMY_CHARACTER;

const appTitle = computed(() => t('home-welcome-to', [t('app-name')]));
const appLead = computed(() => t('home-introduction'));
</script>

<style lang="scss" scoped>
.register-button {
    font-size: 2rem;
    padding: 0.8rem;
    border: 3px solid var(--primary);
    border-radius: 0.5rem;
    background-color: var(--background-2);
    min-width: 50%;
    transition: all 0.3s 0s ease-in-out;
    .register-button-text {
        color: var(--primary)-as-text;
        text-decoration: none;
        font-family: var(--border-color);
    }
}
.register-button:hover {
    background-color: var(--primary);
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

@media (max-width: 400px) {
    .display-3 {
        font-size: 3rem;
    }
}
</style>
