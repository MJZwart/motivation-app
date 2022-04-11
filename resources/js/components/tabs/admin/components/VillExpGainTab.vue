<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <general-form-error />              
            <div class="d-flex">
                <button class="ml-auto m-2" @click="updateVillageExpGain">{{ $t('update-vill-exp-gain') }}</button>
            </div>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th v-for="(field, index) in villageExpGainFields" :key="index">{{field.label}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(exp, itemIndex) in villageExpGain" :key="itemIndex">
                        <td v-for="(field, fieldIndex) in villageExpGainFields" :key="fieldIndex">
                            <span v-if="field.editable">
                                <input v-model="villageExpGain[itemIndex][field.key]" class="w-3" />
                            </span>
                            <span v-else>
                                {{exp[field.key]}}
                            </span>
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
    VILLAGE_EXP_GAIN_FIELDS,
} from '../../../../constants/balancingConstants.js';
import GeneralFormError from '../../../GeneralFormError.vue';
import {shallowRef} from 'vue';
import Loading from '../../../Loading.vue';

export default {
    components: {GeneralFormError, Loading},
    mounted() {
        if (this.village_exp_gain) {
            this.villageExpGain = shallowRef(this.village_exp_gain);
        }
    },
    data() {
        return {
            villageExpGain: null,
            villageExpGainFields: VILLAGE_EXP_GAIN_FIELDS,
        }
    },
    computed: {
        ...mapGetters({
            village_exp_gain: 'admin/getVillageExpGain',
        }),
    },

    methods: {
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