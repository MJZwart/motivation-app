<template>
    <div>
        <BModal :show="showFirstModal" :footer="false" :header="false">
            <template #header>
                <div class="modal-header d-block">
                    <h4>{{ $t('welcome') }}</h4>
                    <p class="silent mb-0">{{ $t('not-yet-done') }}</p>
                </div>
            </template>
            <div>
                <div class="form-group">
                    <label for="rewards-type">{{$t('rewards-type')}}</label>
                    <small class="form-text text-muted mb-2">{{ $t('which-reward-type') }}</small>
                    <div>
                        <input 
                            id="NONE" 
                            v-model="user.rewardsType" 
                            name="rewards-type" 
                            type="radio" 
                            value="NONE" />
                        <label for="NONE">{{ $t('no-rewards') }}</label>
                    </div>
                    <div>
                        <input 
                            id="CHARACTER" 
                            v-model="user.rewardsType" 
                            name="rewards-type" 
                            type="radio" 
                            value="CHARACTER" />
                        <label for="CHARACTER">{{ $t('character-reward') }}</label>
                    </div>
                    <div>
                        <input 
                            id="VILLAGE" 
                            v-model="user.rewardsType" 
                            name="rewards-type" 
                            type="radio" 
                            value="VILLAGE" />
                        <label for="VILLAGE">{{ $t('village-reward') }}</label>
                    </div>
                    <base-form-error name="rewards-type" /> 
                </div>
                <div v-if="user.rewardsType == 'CHARACTER' || user.rewardsType == 'VILLAGE'" class="form-group">
                    <label for="reward_object_name">{{parsedLabelName}}</label>
                    <input 
                        id="reward_object_name" 
                        v-model="user.reward_object_name"
                        type="text" 
                        name="reward_object_name" 
                        :placeholder="parsedLabelName"  />
                    <small class="form-text text-muted">{{$t('change-name-later')}}</small>
                    <base-form-error name="reward-object_name" /> 
                </div>
                <button class="block" @click="nextModal()">{{ $t('next') }}</button>
                <button class="block" variant="danger" @click="logout()">{{ $t('logout')}}</button>
            </div>
        </BModal>
        <BModal :show="showSecondModal" :footer="false" :header="false">
            <template #header>
                <div class="modal-header d-block">
                    <h4>{{ $t('little-more') }}</h4>
                    <p class="silent mb-0">{{ $t('pick-example-tasks') }}</p>
                </div>
            </template>
            <div>
                <div class="form-group">
                    <label for="example-tasks">{{$t('example-tasks')}}</label>
                    <div class="examples-slot">
                        <div v-for="(task, index) in exampleTasks" :key="index">
                            <input
                                :id="task.id"
                                v-model="user.tasks"
                                type="checkbox"
                                name="example-tasks"
                                :value="task.id" />
                            <label class="task-title label-override" :for="task.id">{{task.name}}</label>
                            <small class="form-text text-muted task-description label-override">{{task.description}}</small>
                            <base-form-error name="public-checkbox" /> 
                        </div>
                        <!-- <b-form-checkbox 
                           
                           
                            v-model="user.tasks"
                            :value="task.id" 
                            name="example-tasks">
                            <p>
                                {{task.name}}
                            </p>
                            <p class="">{{task.description}}</p>
                        </b-form-checkbox> -->
                    </div>
                    
                </div>
                <div class="d-flex">
                    <button class="mr-1" @click="startFirstModal()">{{ $t('go-back') }}</button>
                    <button @click="confirmSettings()">{{ $t('submit') }}</button>
                    <button class="ml-auto red" @click="logout()">{{ $t('logout')}}</button>
                </div>
            </div>
        </BModal>
    </div>
</template>

<script>
import BaseFormError from '../components/BaseFormError.vue';
import {mapGetters} from 'vuex';
import BModal from '../components/bootstrap/BModal.vue';
export default {
    components: {BaseFormError, BModal},
    mounted () {
        this.$store.dispatch('clearErrors');
        this.$store.dispatch('task/fetchExampleTasks');
        this.startFirstModal();
    },
    data() {
        return {
            user: {
                rewardsType: 'NONE',
                tasks: [],
                reward_object_name: null,
            },
            showFirstModal: false,
            showSecondModal: false,
        }
    },
    methods: {
        nextModal() {
            if (this.checkInput()) {
                this.showFirstModal = false;
                this.showSecondModal = true;
            }
        },
        startFirstModal() {
            this.showFirstModal = true;
            this.$showSecondModal = false;
        },
        confirmSettings() {
            this.$store.dispatch('user/confirmRegister', this.user);
        },
        checkInput() {
            if (this.user.rewardsType == 'CHARACTER' && !this.user.reward_object_name) {
                this.$store.commit('setErrorMessages', {'reward_object_name': ['No character name given.']});
                return false;
            } else if (this.user.rewardsType == 'VILLAGE' && !this.user.reward_object_name) {
                this.$store.commit('setErrorMessages', {'reward_object_name': ['No village name given.']});
            } else {
                this.$store.dispatch('clearErrors');
                return true;
            }
        },
        logout() {
            this.$store.dispatch('user/logout');
        },
    },
    computed: {
        ...mapGetters({
            exampleTasks: 'task/getExampleTasks',
        }),
        parsedLabelName() {
            if (this.user.rewardsType == 'CHARACTER') {
                return this.$t('character-name');
            } else if (this.user.rewardsType == 'VILLAGE') {
                return this.$t('village-name');
            } else {
                return null;
            }
        },
    },
}
</script>

<style>
.examples-slot{
    max-height:500px;
    overflow-y:scroll;
}
</style>