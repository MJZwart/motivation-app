<template>
    <div>
        <div v-show="firstModal">
            <transition name="modal-fade">
                <div class="modal-backdrop">
                    <div class="modal">
                        <information-block></information-block>

                        <div class="form-title">
                        <h3>Welcome!</h3>
                        <p class="silent">Hold up, you're not quite done yet.</p>
                        </div>
                            <div class="form-group">
                                <label for="name">Rewards type</label>
                                <p class="silent">How would you like to be rewarded for completing your tasks?</p>
                                <b-form-radio-group checked="NONE">
                                    <b-form-radio type="radio" class="input-override" v-model="user.rewardsType" value="NONE">
                                        <p class="radio-label">Nothing, just let me complete tasks.</p>
                                    </b-form-radio>
                                    <b-form-radio type="radio" class="input-override" v-model="user.rewardsType" value="CHARACTER">
                                        <p class="radio-label">RPG Character (Gain experience and level up your character)</p>
                                    </b-form-radio>
                                    <b-form-radio type="radio" class="input-override" v-model="user.rewardsType" value="VILLAGE" disabled>
                                        <p class="radio-label disabled">Village (coming soon)</p>
                                    </b-form-radio>
                                </b-form-radio-group>
                                </div>
                            <div class="form-group" v-if="user.rewardsType == 'CHARACTER'">
                                <label for="character_name">Character name</label>
                                <p class="silent">You can change the name later through your settings.</p>
                                <input 
                                    type="text" 
                                    id="character_name" 
                                    name="character_name" 
                                    placeholder="Character name" 
                                    v-model="user.character_name" />
                            </div>
                            <div class="form-group">
                                <button @click="nextModal()" class="long-button">Next</button>
                            </div>
                    </div>
                </div>
            </transition>
        </div>
        <div v-show="secondModal">
            <transition name="modal-fade">
                <div class="modal-backdrop">
                    <div class="modal">
                        <information-block></information-block>

                        <div class="form-title">
                        <h3>Just a little more</h3>
                        <p class="silent">To get you started, you can pick a few of these example tasks to directly put into your task lists.</p>
                        </div>
                            <div class="form-group">
                                <label for="name">Example tasks</label>
                                <div class="examples-slot">
                                    <b-form-checkbox-group v-model="user.tasks">
                                    <div v-for="task in exampleTasks" :key="task.id">
                                        <b-form-checkbox :value="task.id">
                                            <p class="task-title flex label-override">
                                                {{task.name}}
                                            </p>
                                            <p class="task-description label-override">{{task.description}}</p>
                                        </b-form-checkbox>
                                    </div>
                                    </b-form-checkbox-group>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <button @click="backOneModal()" class="long-button half">Go back</button>
                                <button @click="confirmSettings()" class="long-button half">Submit</button>
                            </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import InformationBlock from '../components/InformationBlock.vue';
import { mapGetters } from 'vuex';
    export default {
        mounted () {
            this.$store.dispatch('task/fetchExampleTasks');
        },
        components: {
            InformationBlock,
        },
        data() {
            return {
                user: {
                    rewardsType: "NONE",
                    tasks: [],
                },
                firstModal: true,
                secondModal: false,
            }
        },
        methods: {
            nextModal() {
                if(this.checkInput()){
                    this.firstModal = false;
                    this.secondModal = true;
                }
            },
            backOneModal(){
                this.firstModal = true;
                this.secondModal = false;
            },
            confirmSettings() {
                this.$store.dispatch('user/confirmRegister', this.user);
            },
            checkInput(){
                if(this.user.rewardsType == "CHARACTER" && !this.user.character_name){
                    this.$store.commit('setResponseMessage', {message: ["No character name given."]});
                    this.$store.commit('setStatus', 'error');
                    return false;
                } else {
                    this.$store.dispatch('clearInformationBlock');
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
    height:500px;
    overflow-y:scroll;
}
.label-override{
    text-transform:none;
    letter-spacing:0;
}
.input-override input{
    width:5%;
}
.input-override label{
    display:inline;
    text-transform:none;
    letter-spacing:0;
    font-weight: 400;
    font-size: 1rem;
}
.radio-label{
    display:inline-block;
}
.disabled {
    text-decoration-line: line-through;
}
.long-button.half{
    width:49%;
}
</style>