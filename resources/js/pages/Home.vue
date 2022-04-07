<template>
    <div class="w-60 center">
        <div class="d-flex">
            <div class="w-80 center jumbotron">
                <h1 class="display-3">{{appTitle}}</h1>
                <p class="lead">{{appLead}}</p>
            </div>
        </div>
        <hr />
        <div class="card-deck mb-3 text-center">
            <div class="card">
                <h5 class="card-header">{{ $t('explanation-tasks-header') }}</h5>
                <TaskList
                    :taskList="dummyList" 
                    class="task-list" />
                <div class="card-text">
                    <p>{{ $t('explanation-tasks') }}</p>
                </div>
            </div>
            <div class="card">
                <h5 class="card-header">{{ $t('explanation-reward-header') }}</h5>
                <Character 
                    :reward="dummyCharacter" 
                    :rewardType="'CHARACTER'"
                    :userReward="true" />
                <div class="card-text">
                    <p>{{ $t('explanation-reward') }}</p>
                </div>
            </div>
        </div>
        <div deck class="card-deck w-50 text-center center">
            <div class="card">
                <h5 class="card-header">{{ $t('explanation-social-header') }}</h5>
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

<script>
import TaskList from '../components/home/DummyTaskList.vue';
import Character from '../components/summary/RewardCard.vue';
import {DUMMY_TASK_LIST, DUMMY_CHARACTER} from '../constants/dummyConstants.js';
export default {
    components: {
        TaskList, Character,
    },
    data() {
        return {
            dummyList: DUMMY_TASK_LIST,
            dummyCharacter: DUMMY_CHARACTER,
        }
    },
    computed: {
        appTitle() {
            return this.$t('home-welcome-to', [this.$t('app-name')]);
        },
        appLead() {
            return this.$t('home-introduction');
        },
    },
}
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
</style>