<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <GeneralFormError />
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

<script setup lang="ts">
import {onMounted, computed, ref, shallowRef} from 'vue';
import {
    CHARACTER_EXP_GAIN_FIELDS, 
} from '/js/constants/balancingConstants.js';
import GeneralFormError from '/js/components/global/GeneralFormError.vue';
import {useAdminStore} from '/js/store/adminStore';
import {useMainStore} from '/js/store/store';
import {CharExpGain} from 'resources/types/admin.js';
const adminStore = useAdminStore();
const mainStore = useMainStore();

onMounted(() => {
    characterExpGain.value = shallowRef(character_exp_gain).value;
    loading.value = false;
}); 

const loading = ref(true);

const characterExpGain = ref<Array<CharExpGain>>([]);
const characterExpGainFields = CHARACTER_EXP_GAIN_FIELDS;

const character_exp_gain = computed<Array<CharExpGain>>(() => adminStore.charExpGain);

function updateCharExpGain() {
    clearErrors();
    adminStore.updateCharExpGain(characterExpGain.value);
}
function clearErrors() {
    mainStore.clearErrors();
}
</script>