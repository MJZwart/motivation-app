<template>
    <div v-if="achievementToEdit">
        <b-form @submit.prevent="updateAchievement">
            <b-form-group
                label="Achievement name" 
                label-for="name">
                <b-form-input  
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Name" 
                    v-model="achievementToEdit.name" />
            </b-form-group>
            <b-form-group
                label="Achievement description" 
                label-for="description">
                <b-form-input  
                    type="text" 
                    id="description" 
                    name="description" 
                    placeholder="Description" 
                    v-model="achievementToEdit.description" />
            </b-form-group>
            <b-form-group
                label="Achievement type" 
                label-for="type">
                <b-select 
                    id="type" 
                    v-model="achievementToEdit.trigger_type"
                    :options="achievementTriggers"
                    value-field="trigger_type"
                    text-field="trigger_type">
                </b-select>
            </b-form-group>
            <b-form-group
                label="Trigger amount" 
                label-for="amount">
                <b-form-input  
                    type="number" 
                    id="amount" 
                    name="amount" 
                    placeholder="Amount" 
                    v-model="achievementToEdit.trigger_amount" />
            </b-form-group>
            <b-form-group
                label="Trigger description" 
                label-for="trigger-description">
                <p v-if="achievementToEdit.trigger_type" id="trigger-description">{{triggerDescription}}</p>
            </b-form-group>
            <b-button type="submit" block>{{ $t('edit-achievement') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import {mapGetters} from 'vuex';
export default {
    props: {
        achievement: Object,
    },
    mounted() {
        this.achievementToEdit = this.achievement;
    },
    components: {
        BaseFormError,
    },
    data() {
        return {
            achievementToEdit: {
                
            },
        }
    },
    methods: {
        updateAchievement(){
            this.$store.dispatch('admin/editAchievement', this.achievementToEdit).then(response => {
                this.close();
            });

        },
        close(){
            this.achievementToEdit = {},
            this.$emit('close');
        }
    },
    computed: {
        ...mapGetters({
            achievementTriggers: 'achievement/getAchievementTriggers',
        }),
        triggerDescription() {
            if(this.achievementToEdit.trigger_type){
                const plural = this.achievement.trigger_amount > 1 ? 's' : '';
                let desc = this.achievementTriggers.find(item => item.trigger_type === this.achievement.trigger_type);
                desc = desc.trigger_description.replace('%d', this.achievement.trigger_amount);
                return desc.replace('%s', plural);
            }
        },
    },
    
}
</script>
