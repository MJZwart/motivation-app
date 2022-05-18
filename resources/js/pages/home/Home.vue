<template>
    <div class="w-60-flex center">
        <div class="d-flex">
            <div class="w-80 center jumbotron">
                <h1 class="display-3">{{appTitle}}</h1>
                <p class="lead">{{appLead}}</p>
            </div>
        </div>
        <hr />
        <div class="card-deck mb-3 text-center">
            <div class="card mb-3">
                <div class="card-header">{{ $t('explanation-tasks-header') }}</div>
                <div class="card-body">
                    <TaskList
                        :taskList="dummyList" 
                        class="task-list mt-2" />
                    <div class="card-text">
                        <p>{{ $t('explanation-tasks') }}</p>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">{{ $t('explanation-reward-header') }}</div>
                <div class="card-body">
                    <Character 
                        :reward="dummyCharacter" 
                        :rewardType="'CHARACTER'"
                        :userReward="true" />
                    <div class="card-text">
                        <p>{{ $t('explanation-reward') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div deck class="card-deck w-50 text-center center">
            <div class="card">
                <div class="card-header">{{ $t('explanation-social-header') }}</div>
                <p>{{ $t('explanation-social') }}</p>
            </div>
        </div>
        <div class="d-flex">
            <button class="large-button center mt-3">
                <router-link to="/register" class="primary-text no-hover">{{ $t('create-account-today') }}</router-link>
            </button>
        </div>
    </div>
</template>

<script setup>
import TaskList from './DummyTaskList.vue';
import Character from '/js/pages/dashboard/components/RewardCard.vue';
import {DUMMY_TASK_LIST, DUMMY_CHARACTER} from '/js/constants/dummyConstants.js';
import {computed} from 'vue';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope

const dummyList = DUMMY_TASK_LIST;
const dummyCharacter = DUMMY_CHARACTER;

const appTitle = computed(() => t('home-welcome-to', [t('app-name')]));
const appLead = computed(() => t('home-introduction'));
</script>

<style scoped>
.large-button {
    font-size: 2rem;
    padding: 1.5rem;
    border-radius: 3rem;
}
.display-3{
    font-size:4rem;
}
.w-60-flex {
    max-width: 850px;
}
@media (max-width:650px){
    .card-deck{
        flex-flow: column wrap;
    }
}
</style>