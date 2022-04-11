<template>
    <div class="row">
        <div class="col">
            <!-- 
                Button to update the changed experience points in the table
                -->
            <button class="mt-4" @click="updateExpPoints">{{ $t('update-exp-points') }}</button>
            <general-form-error />
            <hr />
            <!-- 
                Fields to add a new level
                -->
            <div class="m-1">
                <h5>{{$t('add-new-level')}}</h5>
                <div class="form-group">
                    <label for="level">{{$t('level')}}</label>
                    <input  
                        id="level" 
                        v-model="newLevel.level"
                        type="number" 
                        name="level" 
                        :placeholder="$t('level')"  />
                    <base-form-error name="level" /> 
                </div>
                <div class="form-group">
                    <label for="points">{{$t('points')}}</label>
                    <input  
                        id="points" 
                        v-model="newLevel.experience_points"
                        type="number" 
                        name="points" 
                        :placeholder="$t('points')"  />
                    <base-form-error name="experience_points" /> 
                </div>
                <button @click="addNewLevel">{{ $t('add-level') }}</button>
            </div>
        </div>
        <!-- 
            The Experience points table
            -->
        <div class="col">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(level, index) in experiencePoints" :key="index">
                        <td>{{level.level}}</td>
                        <td>
                            <input v-model="experiencePoints[index].experience_points" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import {
    EXPERIENCE_POINTS_FIELDS, 
} from '../../../../constants/balancingConstants.js';
import GeneralFormError from '../../../GeneralFormError.vue';
import {shallowRef} from 'vue';
import BaseFormError from '../../../BaseFormError.vue';
export default {
    components: {GeneralFormError, BaseFormError},
    mounted() {
        if (this.experience_points) {
            this.experiencePoints = shallowRef(this.experience_points);
            this.loading = false;
        }
    },
    data() {
        return {
            experiencePoints: null,
            experiencePointsFields: EXPERIENCE_POINTS_FIELDS,
            newLevel: {},
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
        updateExpPoints() {
            this.clearErrors();
            this.$store.dispatch('admin/updateExpPoints', this.experiencePoints);
        },
        addNewLevel() {
            this.clearErrors();
            this.$store.dispatch('admin/addNewLevel', this.newLevel).then(() => {
                this.experiencePoints = shallowRef(this.experience_points);
            });
        },
        clearErrors() {
            this.$store.dispatch('clearErrors');
        },
    },
}
</script>