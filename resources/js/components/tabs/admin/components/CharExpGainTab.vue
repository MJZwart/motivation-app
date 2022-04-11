<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <general-form-error />
            <div class="d-flex">
                <button class="ml-auto m-2" @click="updateCharExpGain">{{ $t('update-char-exp-gain') }}</button>
            </div>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th v-for="(field, index) in characterExpGainFields" :key="index">{{field.label}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(exp, itemIndex) in characterExpGain" :key="itemIndex">
                        <td v-for="(field, fieldIndex) in characterExpGainFields" :key="fieldIndex">
                            <span v-if="field.editable">
                                <input v-model="characterExpGain[itemIndex][field.key]" class="w-3" />
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
    CHARACTER_EXP_GAIN_FIELDS, 
} from '../../../../constants/balancingConstants.js';
import GeneralFormError from '../../../GeneralFormError.vue';
import Vue from 'vue';
import Loading from '../../../Loading.vue';

export default {
    components: {GeneralFormError, Loading},
    mounted() {
        if (this.character_exp_gain) {
            this.characterExpGain = Vue.util.extend([], this.character_exp_gain);
        }
    },
    data() {
        return {
            characterExpGain: null,
            characterExpGainFields: CHARACTER_EXP_GAIN_FIELDS,
        }
    },
    computed: {
        ...mapGetters({
            character_exp_gain: 'admin/getCharExpGain',
        }),
    },

    methods: {
        updateCharExpGain() {
            this.clearErrors();
            this.$store.dispatch('admin/updateCharExpGain', this.characterExpGain);
        },
        clearErrors() {
            this.$store.dispatch('clearErrors');
        },
    },
}
</script>