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

<script setup>
import {
    VILLAGE_EXP_GAIN_FIELDS,
} from '/js/constants/balancingConstants.js';
import GeneralFormError from '/js/components/global/GeneralFormError.vue';
import {shallowRef, computed, onMounted, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';
import {useMainStore} from '/js/store/store';
const adminStore = useAdminStore();
const mainStore = useMainStore();

onMounted(() => {
    villageExpGain.value = shallowRef(village_exp_gain).value;
});

const villageExpGain = ref(null);
const villageExpGainFields = VILLAGE_EXP_GAIN_FIELDS;

const village_exp_gain = computed(() => adminStore.villageExpGain);

function updateVillageExpGain() {
    clearErrors();
    adminStore.updateVillageExpGain(villageExpGain.value);
}
function clearErrors() {
    mainStore.clearErrors();
}
</script>