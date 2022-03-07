<template>
    <div v-if="!loading">
        <b-tabs>
            <b-tab :title="$t('exp-points')">
                <general-form-error />
                <div class="d-flex">
                    <b-button class="ml-auto m-2" @click="updateExpPoints">{{ $t('update-exp-points') }}</b-button>
                </div>
                <b-editable-table 
                    v-model="experiencePoints" 
                    :fields="experiencePointsFields" 
                    class="balancing-table"
                    hover foot-clone
                    @input-change="handleInput" />
            </b-tab>
            <b-tab :title="$t('char-exp-gain')">
                <general-form-error />
                <div class="d-flex">
                    <b-button class="ml-auto m-2" @click="updateCharExpGain">{{ $t('update-char-exp-gain') }}</b-button>
                </div>
                <b-editable-table
                    v-model="characterExpGain"
                    :fields="characterExpGainFields"
                    class="balancing-table"
                    small
                    @input-change="handleInput" />
            </b-tab>
            <b-tab :title="$t('vill-exp-gain')">  
                <general-form-error />              
                <div class="d-flex">
                    <b-button class="ml-auto m-2" @click="updateVillageExpGain">{{ $t('update-vill-exp-gain') }}</b-button>
                </div>
                <b-editable-table
                    v-model="villageExpGain"
                    :fields="villageExpGainFields"
                    class="balancing-table"
                    small
                    @input-change="handleInput" />
            </b-tab>
        </b-tabs>
    </div>    
</template>

<script>
import {mapGetters} from 'vuex';
import {
    EXPERIENCE_POINTS_FIELDS, 
    CHARACTER_EXP_GAIN_FIELDS, 
    VILLAGE_EXP_GAIN_FIELDS,
} from '../../../constants/balancingConstants.js';
import BEditableTable from 'bootstrap-vue-editable-table';
import GeneralFormError from '../../GeneralFormError.vue';
import Vue from 'vue';

export default {
    components: {GeneralFormError, BEditableTable},
    mounted() {
        if (this.experience_points && this.character_exp_gain && this.village_exp_gain) {
            this.experiencePoints = Vue.util.extend([], this.experience_points);
            this.characterExpGain = Vue.util.extend([], this.character_exp_gain);
            this.villageExpGain = Vue.util.extend([], this.village_exp_gain);
            this.loading = false;
        }
    },
    data() {
        return {
            experiencePoints: null,
            characterExpGain: null,
            villageExpGain: null,
            loading: true,
            experiencePointsFields: EXPERIENCE_POINTS_FIELDS,
            characterExpGainFields: CHARACTER_EXP_GAIN_FIELDS,
            villageExpGainFields: VILLAGE_EXP_GAIN_FIELDS,
        }
    },
    computed: {
        ...mapGetters({
            experience_points: 'admin/getExperiencePoints',
            character_exp_gain: 'admin/getCharExpGain',
            village_exp_gain: 'admin/getVillageExpGain',
        }),

    },

    methods: {
        handleInput() {},
        updateExpPoints() {
            this.clearErrors();
            this.$store.dispatch('admin/updateExpPoints', this.experiencePoints);
        },
        updateCharExpGain() {
            this.clearErrors();
            this.$store.dispatch('admin/updateCharExpGain', this.characterExpGain);
        },
        updateVillageExpGain() {
            this.clearErrors();
            this.$store.dispatch('admin/updateVillageExpGain', this.villageExpGain);
        },
        clearErrors() {
            this.$store.dispatch('clearErrors');
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