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
                        <th v-for="(field, index) in characterExpGainFields" :key="index">{{ $t(field.label)}}</th>
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
import {onMounted, ref} from 'vue';
import {
    CHARACTER_EXP_GAIN_FIELDS, 
} from '/js/constants/balancingConstants.js';
import GeneralFormError from '/js/components/global/GeneralFormError.vue';
import {useAdminStore} from '/js/store/adminStore';
import {CharExpGain} from 'resources/types/admin.js';
import {clearErrors} from '/js/services/errorService';

const adminStore = useAdminStore();

onMounted(async() => {
    characterExpGain.value = await adminStore.getCharacterExpGain();
    loading.value = false;
}); 

const loading = ref(true);

const characterExpGain = ref<Array<CharExpGain>>([]);
const characterExpGainFields = CHARACTER_EXP_GAIN_FIELDS;

async function updateCharExpGain() {
    clearErrors();
    characterExpGain.value = await adminStore.updateCharExpGain(characterExpGain.value);
}
</script>