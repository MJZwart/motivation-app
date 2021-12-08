<template>
    <div>
        <b-modal id="first-modal" hide-footer hide-header-close no-close-on-backdrop no-close-on-esc>
            <template #modal-title>
                <h2>{{ $t('welcome') }}</h2>
                <p class="silent mb-0">Hold up, you're not quite done yet.</p>
            </template>
            <div>
                <b-form-group
                    label="Rewards type"
                    label-for="rewards-type">
                    <b-form-text class="text-muted mb-2">How would you like to be rewarded for completing your tasks?</b-form-text>
                    <b-form-radio-group :checked="user.rewardsType">
                        <b-form-radio type="radio" class="input-override" v-model="user.rewardsType" value="NONE" name="rewards-type">
                            <p class="radio-label">Nothing, just let me complete tasks.</p>
                        </b-form-radio>
                        <b-form-radio type="radio" class="input-override" v-model="user.rewardsType" value="CHARACTER" name="rewards-type">
                            <p class="radio-label">RPG Character (Gain experience and level up your character)</p>
                        </b-form-radio>
                        <b-form-radio type="radio" class="input-override" v-model="user.rewardsType" value="VILLAGE" name="rewards-type" disabled>
                            <p class="radio-label disabled">Village (coming soon)</p>
                        </b-form-radio>
                    </b-form-radio-group>
                    
                <base-form-error name="rewards-type" /> 
                </b-form-group>
                <b-form-group v-if="user.rewardsType == 'CHARACTER'"
                    :label="$t('character-name')"
                    label-for="character_name"
                    description="You can change the name later through your settings.">
                    <b-form-input 
                        type="text" 
                        id="character_name" 
                        name="character_name" 
                        placeholder="Character name" 
                        v-model="user.character_name" />
                <base-form-error name="character_name" /> 
                </b-form-group>
                <b-button @click="nextModal()" block>Next</b-button>
            </div>
        </b-modal>
        <b-modal id="second-modal" hide-footer hide-header-close no-close-on-backdrop no-close-on-esc>
            <template #modal-title>
                <h2>{{ $t('little-more') }}</h2>
                <p class="silent mb-0">To get you started, you can pick a few of these example tasks to directly put into your task lists.</p>
            </template>
            <div>
                <b-form-group
                    label="Example tasks"
                    label-for="example-tasks">
                    <div class="examples-slot">
                        <b-form-checkbox 
                            v-for="task in exampleTasks"
                            v-model="user.tasks"
                            :value="task.id" 
                            :key="task.id"
                            name="example-tasks">
                            <p class="task-title d-flex label-override">
                                {{task.name}}
                            </p>
                            <p class="task-description label-override">{{task.description}}</p>
                        </b-form-checkbox>
                    </div>
                    
                </b-form-group>
                <b-button @click="startFirstModal()" class="long-button half">Go back</b-button>
                <b-button @click="confirmSettings()" class="long-button half">Submit</b-button>
            </div>
        </b-modal>
    </div>
</template>

<script>
import BaseFormError from '../components/BaseFormError.vue';
import { mapGetters } from 'vuex';
    export default {
        components: { BaseFormError },
        mounted () {
            this.$store.dispatch('clearErrors');
            this.$store.dispatch('task/fetchExampleTasks');
            this.startFirstModal();
        },
        data() {
            return {
                user: {
                    rewardsType: "NONE",
                    tasks: [],
                },
            }
        },
        methods: {
            nextModal() {
                if(this.checkInput()){
                    this.$bvModal.hide('first-modal');
                    this.$bvModal.show('second-modal');
                }
            },
            startFirstModal(){
                this.$bvModal.show('first-modal');
                this.$bvModal.hide('second-modal');
            },
            confirmSettings() {
                this.$store.dispatch('user/confirmRegister', this.user);
            },
            checkInput(){
                if(this.user.rewardsType == "CHARACTER" && !this.user.character_name){
                    this.$store.commit('setErrorMessages', {"character_name": ["No character name given."]});
                    return false;
                } else {
                    this.$store.dispatch('clearErrors');
                    return true;
                }
            },
        },
        computed: {
            ...mapGetters({
                exampleTasks: 'task/getExampleTasks',
            }),
        },
    }
</script>

<style>
.examples-slot{
    max-height:500px;
    overflow-y:scroll;
}
</style>