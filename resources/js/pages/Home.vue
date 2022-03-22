<template>
    <div>
        <div class="d-flex">
            <b-jumbotron class="center" :header="appTitle" :lead="appLead" />
        </div>
        <hr />
        <b-card-group deck>
            <b-card>
                <TaskList
                    :taskList="dummyList" 
                    class="task-list" />
                <b-card-text>
                    <p>{{ $t('explanation-tasks') }}</p>
                </b-card-text>
            </b-card>
            <b-card>
                <Character 
                    :reward="dummyCharacter" 
                    :rewardType="'CHARACTER'"
                    :userReward="true" />
                <b-card-text>
                    <p>{{ $t('explanation-reward') }}</p>
                </b-card-text>
            </b-card>
        </b-card-group>
        <!-- <h1>{{ $t('home-welcome-to', [$t('app-name')]) }}</h1>
        <p>{{ $t('home-introduction') }}</p>
        <h3>{{ $t('home-abilities') }}</h3>
        <ul>
            <li>{{ $t('home-abilities-1') }}</li>
            <li>{{ $t('home-abilities-2') }}</li>
            <li>{{ $t('home-abilities-3') }}</li>
        </ul>
        <h3>{{ $t('home-and-more') }}</h3>
        <p>{{ $t('home-2') }}</p> -->
        <!-- 
        <b-button block to="/login">Login now</b-button>
        <b-button block to="/register">Create a new account</b-button> -->
    </div>
</template>

<script>
// import taskImage from '../../assets/images/Tasks.png'
import TaskList from '../components/home/DummyTaskList.vue';
import Character from '../components/summary/RewardCard.vue';
export default {
    components: {
        TaskList, Character,
    },
    //TODO move the data
    // eslint-disable-next-line max-lines-per-function
    data() {
        return {
            dummyList: {
                name: 'Tasks',
                tasks: [
                    {id: 0, name: 'Take the stairs', description: 'Take the stairs instead of the elevator.'},
                    {
                        id: 1, 
                        name: 'Weekly clean up', 
                        description: 'Clean your home/room, water your plants, take out the trash, etc.',
                        tasks: [{id: 2, name: 'Clean the desk', description: ''}],
                    },
                ],
            },
            dummyCharacter: {
                name: 'Dummy thicc', 
                a: 1,
                a_exp: 100,
                b: 1,
                b_exp: 150,
                c: 1,
                c_exp: 50,
                d: 1,
                d_exp: 450,
                e: 1,
                e_exp: 800,
                experience: 500,
                level: 1,
                experienceTable: [
                    {id: 0, level: 1, experience_points: 1000},
                    {id: 1, level: 2, experience_points: 1200},
                    {id: 2, level: 3, experience_points: 1500},
                ],
            },
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
    methods: {
        showNewTask(superTask, taskList) {
            this.$store.dispatch('clearErrors');
            this.superTask = superTask;
            this.taskList = taskList;
            this.$bvModal.show('new-task');
        },
        closeNewTask() {
            this.$bvModal.hide('new-task');
        },

        /** Shows and hides the modal to edit a given task
         * @param {import('../../types/task').Task} task
         */
        showEditTask(task) {
            this.$store.dispatch('clearErrors');
            this.taskToEdit = task;
            this.$bvModal.show('edit-task');
        },
        closeEditTask() {
            this.$bvModal.hide('edit-task');
        },
    },
}
</script>