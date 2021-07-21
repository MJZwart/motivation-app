<template>
    <div>
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal">
                    <information-block></information-block>
                    <div class="form-title">
                    <h3>New achievement</h3>
                    </div>
                    <form @submit.prevent="submitAchievement">
                        <div class="form-group">
                            <label for="name">Achievement name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Name" 
                                v-model="achievement.name" />
                        </div>
                        <div class="form-group">
                            <label for="description">Achievement description</label>
                            <input 
                                type="text" 
                                id="description" 
                                name="description" 
                                placeholder="Description" 
                                v-model="achievement.description" />
                        </div>
                        <div class="form-group">
                            <label for="type">Achievement type</label>
                            <select 
                                    id="type" 
                                    v-model="achievement.trigger_type">
                                    <option v-for="option in achievementTriggers" :value="option.trigger_type" :key="option.key">{{option.trigger_type}}</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Trigger amount</label>
                            <input 
                                type="number" 
                                id="amount" 
                                name="amount" 
                                placeholder="Amount" 
                                v-model="achievement.trigger_amount" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="long-button">Create new achievement</button>
                            <button type="button" class="long-button" @click="close">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </transition>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import InformationBlock from './InformationBlock.vue';
export default {
    components: {
        InformationBlock,
    },
    data() {
        return {
            achievement: {},
        }
    },
    methods: {
        submitAchievement(){
            var self = this;
            this.$store.dispatch('admin/newAchievement', this.achievement).then(function(){
                self.close();
            });

        },
        close(){
            this.achievement = {},
            this.$emit('close');
        }
    },
    computed: {
        ...mapGetters({
            achievementTriggers: 'achievement/getAchievementTriggers',
        }),
    },
    
}
</script>
