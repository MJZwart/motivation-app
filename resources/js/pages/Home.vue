<template>
    <div class="w-60 center">
        <div class="d-flex">
            <b-jumbotron class="w-80 center" :header="appTitle" :lead="appLead" />
        </div>
        <hr />
        <b-card-group deck class="mb-3 text-center">
            <b-card>
                <h5>{{ $t('explanation-tasks-header') }}</h5>
                <TaskList
                    :taskList="dummyList" 
                    class="task-list" />
                <hr />
                <b-card-text>
                    <p>{{ $t('explanation-tasks') }}</p>
                </b-card-text>
            </b-card>
            <b-card>
                <h5>{{ $t('explanation-reward-header') }}</h5>
                <Character 
                    :reward="dummyCharacter" 
                    :rewardType="'CHARACTER'"
                    :userReward="true" />
                <b-card-text>
                    <p>{{ $t('explanation-reward') }}</p>
                </b-card-text>
            </b-card>
        </b-card-group>
        <b-card-group deck class="w-50 text-center center">
            <b-card>
                <h5>{{ $t('explanation-social-header') }}</h5>
                <p>{{ $t('explanation-social') }}</p>
            </b-card>
        </b-card-group>
        <div class="d-flex">
            <b-button class="large-button center mt-3" to="/register">{{ $t('create-account-today') }}</b-button>
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
</style>