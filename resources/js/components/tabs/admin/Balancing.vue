<template>
    <div v-if="!loading">
        <b-tabs>
            <b-tab title="Experience points">
                <div class="d-flex">
                    <b-button class="ml-auto m-2" @click="updateExpPoints">Update experience points</b-button>
                </div>
                <b-editable-table 
                    v-model="experiencePoints" 
                    :fields="experiencePointsFields" 
                    class="balancing-table experience-table"
                    hover foot-clone
                    @input-change="handleInput" />
            </b-tab>
            <b-tab title="Character XP gain" active>
                <div class="d-flex">
                    <b-button class="ml-auto m-2" @click="updateCharExpGain">Update</b-button>
                </div>
                <b-editable-table
                    v-model="characterExpGain"
                    :fields="characterExpGainFields"
                    class="balancing-table character-table"
                    small
                    @input-change="handleInput" />
            </b-tab>
            <b-tab title="Village XP gain">Vill exp</b-tab>
        </b-tabs>
    </div>    
</template>

<script>
import {mapGetters} from 'vuex';
import {experiencePointsFields, characterExpGainFields} from '../../../constants/balancingConstants.js';
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
            characterExpGainFields: characterExpGainFields,
        }
    },
    computed: {
        ...mapGetters({
            balancing: 'admin/getBalancing',
        }),

    },

    methods: {
        handleInput() {},
        updateExpPoints() {
            // console.log(this.experiencePoints);
            this.$store.dispatch('admin/updateExpPoints', this.experiencePoints);
        },
        updateCharExpGain() {
            console.log('cchar');
        },
    },
}
</script>

<style lang="scss">
.points-col {
    width:10rem;
    .form-control {
        width:8rem !important;
    }
}
table.balancing-table .data-cell {
    height:2.5rem;
    padding:0.48rem;
    padding-left: 0.81rem;
}
table.balancing-table td{
    .form-control {
        width:3rem;
    }
}
</style>