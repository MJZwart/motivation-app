<template>
    <div v-if="achievementToEdit">
        <b-form @submit.prevent="updateAchievement">
            <b-form-group
                :label="$t('achievement-name')" 
                label-for="name">
                <b-form-input  
                    id="name" 
                    v-model="achievementToEdit.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-form-group
                :label="$t('achievement-description')" 
                label-for="description">
                <b-form-input  
                    id="description" 
                    v-model="achievementToEdit.description"
                    type="text" 
                    name="description" 
                    :placeholder="$t('description')"  />
                <base-form-error name="description" /> 
            </b-form-group>
            <b-form-group
                :label="$t('trigger-type')" 
                label-for="type">
                <b-select 
                    id="type" 
                    v-model="achievementToEdit.trigger_type"
                    :options="achievementTriggers"
                    value-field="trigger_type"
                    text-field="trigger_type" />
                <base-form-error name="trigger_type" /> 
            </b-form-group>
            <b-form-group
                :label="$t('trigger-amount')" 
                label-for="trigger_amount">
                <b-form-input  
                    id="trigger_amount" 
                    v-model="achievementToEdit.trigger_amount"
                    type="number" 
                    name="trigger_amount" 
                    :placeholder="$t('amount')"  />
                <base-form-error name="trigger_amount" /> 
            </b-form-group>
            <b-form-group
                :label="$t('trigger-description')" 
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
import Vue from 'vue';
export default {
    props: {
        achievement: {
            /** @type {import('resources/types/achievement').Achievement} */
            type: Object,
            required: true,
        },
    },
    mounted() {
        this.achievementToEdit = Vue.util.extend({}, this.achievement);
    },
    components: {
        BaseFormError,
    },
    data() {
        return {
            /** @type {import('resources/types/achievement').Achievement} */
            achievementToEdit: {
                
            },
        }
    },
    methods: {
        updateAchievement() {
            this.$store.dispatch('achievement/editAchievement', this.achievementToEdit).then(() => {
                this.close();
            });

        },
        close() {
            this.achievementToEdit = {},
            this.$emit('close');
        },
    },
    computed: {
        ...mapGetters({
            achievementTriggers: 'achievement/getAchievementTriggers',
        }),
        /** Parses the achievement description from the type (eg Made {0} friends) and the amount */
        triggerDescription() {
            const plural = this.achievement.trigger_amount > 1 ? 's' : '';
            let desc = this.achievementTriggers.find(item => item.trigger_type === this.achievement.trigger_type);
            desc = desc.trigger_description.replace('%d', this.achievement.trigger_amount);
            return desc.replace('%s', plural);
        },
    },
}
</script>
