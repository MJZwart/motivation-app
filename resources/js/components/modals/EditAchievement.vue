<template>
    <div v-if="achievementToEdit">
        <form @submit.prevent="updateAchievement">
            <div class="form-group">
                <label for="name">{{$t('achievement-name')}}</label>
                <input  
                    id="name" 
                    v-model="achievementToEdit.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </div>
            <div class="form-group">
                <label for="description">{{$t('achievement-description')}}</label>
                <input  
                    id="description" 
                    v-model="achievementToEdit.description"
                    type="text" 
                    name="description" 
                    :placeholder="$t('description')"  />
                <base-form-error name="description" /> 
            </div>
            <div class="form-group">
                <label for="type">{{$t('trigger-type')}}</label>
                <select 
                    id="type" 
                    v-model="achievementToEdit.trigger_type"
                    value-field="trigger_type"
                    text-field="trigger_type">
                    <option v-for="(option, index) in achievementTriggers" :key="index" :value="option.value">
                        {{option.text}}
                    </option>
                </select>
                <base-form-error name="trigger_type" /> 
            </div>
            <div class="form-group">
                <label for="trigger_amount">{{$t('trigger-amount')}}</label>
                <input  
                    id="trigger_amount" 
                    v-model="achievementToEdit.trigger_amount"
                    type="number" 
                    name="trigger_amount" 
                    :placeholder="$t('amount')"  />
                <base-form-error name="trigger_amount" /> 
            </div>
            <div class="form-group">
                <label for="trigger-description">{{$t('trigger-description')}}</label>
                <p v-if="achievementToEdit.trigger_type" id="trigger-description">{{triggerDescription}}</p>
            </div>
            <button type="submit" class="block">{{ $t('edit-achievement') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
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
