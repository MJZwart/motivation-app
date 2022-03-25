<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <b-tabs>
                <b-tab :title="$t('exp-points')">
                    <b-row>
                        <b-col>
                            <!-- 
                                Button to update the changed experience points in the table
                             -->
                            <b-button class="mt-4" @click="updateExpPoints">{{ $t('update-exp-points') }}</b-button>
                            <general-form-error />
                            <hr />
                            <!-- 
                                Fields to add a new level
                             -->
                            <div class="m-1">
                                <h5>{{$t('add-new-level')}}</h5>
                                <b-form-group
                                    :label="$t('level')" 
                                    label-for="level">
                                    <b-form-input  
                                        id="level" 
                                        v-model="newLevel.level"
                                        type="number" 
                                        name="level" 
                                        :placeholder="$t('level')"  />
                                    <base-form-error name="level" /> 
                                </b-form-group>
                                <b-form-group
                                    :label="$t('points')" 
                                    label-for="points">
                                    <b-form-input  
                                        id="points" 
                                        v-model="newLevel.experience_points"
                                        type="number" 
                                        name="points" 
                                        :placeholder="$t('points')"  />
                                    <base-form-error name="experience_points" /> 
                                </b-form-group>
                                <b-button @click="addNewLevel">{{ $t('add-level') }}</b-button>
                            </div>
                        </b-col>
                        <!-- 
                            The Experience points table
                         -->
                        <b-col>
                            <b-editable-table 
                                v-model="experiencePoints" 
                                :fields="experiencePointsFields" 
                                class="balancing-table"
                                hover foot-clone
                                @input-change="handleInput" />
                        </b-col>
                    </b-row>
                </b-tab>
                <!-- 
                    The tab for Character Exp gain
                 -->
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
                <!-- 
                    The tab for Village Exp gain
                 -->
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
import BaseFormError from '../../BaseFormError.vue';
import Loading from '../../Loading.vue';

export default {
    components: {GeneralFormError, BEditableTable, BaseFormError, Loading},
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
            experiencePointsFields: EXPERIENCE_POINTS_FIELDS,
            characterExpGainFields: CHARACTER_EXP_GAIN_FIELDS,
            villageExpGainFields: VILLAGE_EXP_GAIN_FIELDS,
            newLevel: {},
            loading: true,
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
        addNewLevel() {
            this.clearErrors();
            this.$store.dispatch('admin/addNewLevel', this.newLevel).then(() => {
                this.experiencePoints = Vue.util.extend([], this.experience_points);
            });
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