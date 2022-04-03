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
                    <b-form-text class="text-muted mb-2">{{ $t('which-reward-type') }}</b-form-text>
                    <b-form-radio-group :checked="user.rewardsType">
                        <b-form-radio v-model="user.rewardsType" type="radio" 
                                      class="input-override" value="NONE" name="rewards-type">
                            <p class="radio-label">{{ $t('no-rewards') }}</p>
                        </b-form-radio>
                        <b-form-radio v-model="user.rewardsType" type="radio" 
                                      class="input-override" value="CHARACTER" name="rewards-type">
                            <p class="radio-label">{{ $t('character-reward') }}</p>
                        </b-form-radio>
                        <b-form-radio v-model="user.rewardsType" type="radio" 
                                      class="input-override" value="VILLAGE" name="rewards-type">
                            <p class="radio-label">{{ $t('village-reward') }}</p>
                        </b-form-radio>
                    </b-form-radio-group>
                    <base-form-error name="rewards-type" /> 
                </div>
                <div v-if="user.rewardsType == 'CHARACTER' || user.rewardsType == 'VILLAGE'" class="form-group">
                    <label for="reward_object_name">{{parsedLabelName}}</label>
                    <input 
                        id="reward_object_name" 
                        v-model="user.reward_object_name"
                        class="form-control"
                        type="text" 
                        name="reward_object_name" 
                        :placeholder="parsedLabelName"  />
                    <small class="form-text text-muted">{{$t('change-name-later')}}</small>
                    <base-form-error name="reward-object_name" /> 
                </div>
                <b-button block @click="nextModal()">{{ $t('next') }}</b-button>
                <b-button block variant="danger" @click="logout()">{{ $t('logout')}}</b-button>
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
                        <b-form-checkbox 
                            v-for="task in exampleTasks"
                            :key="task.id"
                            v-model="user.tasks"
                            :value="task.id" 
                            name="example-tasks">
                            <p class="task-title d-flex label-override">
                                {{task.name}}
                            </p>
                            <p class="task-description label-override">{{task.description}}</p>
                        </b-form-checkbox>
                    </div>
                    
                </div>
                <div class="d-flex">
                    <b-button class="mr-1" @click="startFirstModal()">{{ $t('go-back') }}</b-button>
                    <b-button @click="confirmSettings()">{{ $t('submit') }}</b-button>
                    <b-button class="ml-auto" variant="danger" @click="logout()">{{ $t('logout')}}</b-button>
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