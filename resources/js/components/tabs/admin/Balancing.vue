<template>
    <div v-if="!loading">
        <b-tabs>
            <b-tab title="Experience points" active>
                <div class="d-flex">
                    <b-button class="ml-auto m-2" @click="updateExpPoints">Update experience points</b-button>
                </div>
                <b-editable-table 
                    v-model="experiencePoints" 
                    :fields="experiencePointsFields" 
                    class="exp-table" 
                    @input-change="handleInput" />
            </b-tab>
            <b-tab title="Character XP gain">Char exp</b-tab>
            <b-tab title="Village XP gain">Vill exp</b-tab>
        </b-tabs>
    </div>    
</template>

<script>
import {mapGetters} from 'vuex';
import {experiencePointsFields} from '../../../constants/balancingConstants.js';
import BEditableTable from 'bootstrap-vue-editable-table';
import BaseFormError from '../../BaseFormError.vue';
import Vue from 'vue';

export default {
    components: {BaseFormError, BEditableTable},
    mounted() {
        if (this.balancing) {
            this.experiencePoints = Vue.util.extend([], this.balancing.experience_points);
            this.characterExpGain = Vue.util.extend([], this.balancing.character_exp_gain);
            this.villageExpGain = Vue.util.extend([], this.balancing.village_exp_gain);
            this.loading = false;
        }
    },
    data() {
        return {
            experiencePoints: null,
            characterExpGain: null,
            villageExpGain: null,
            loading: true,
            experiencePointsFields: experiencePointsFields,
        }
    },
    computed: {
        ...mapGetters({
            balancing: 'admin/getBalancing',
        }),

    },

    methods: {
        updateExpPoints() {
            console.log(this.experiencePoints);
        },
        handleInput() {
        },
    },
}
</script>

<style lang="scss" scoped>
.exp-table {

    td .cell-data {
        padding:5rem;
        margin:5rem;
    }
}
</style>