<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <GeneralFormError />              
            <div class="d-flex">
                <button class="ml-auto m-2" @click="updateVillageExpGain">{{ $t('update-vill-exp-gain') }}</button>
            </div>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th v-for="(field, index) in VILLAGE_EXP_GAIN_FIELDS" :key="index">{{ $t(field.label) }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(exp, itemIndex) in villageExpGain" :key="itemIndex">
                        <td v-for="(field, fieldIndex) in VILLAGE_EXP_GAIN_FIELDS" :key="fieldIndex">
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

<script setup lang="ts">
import {
    VILLAGE_EXP_GAIN_FIELDS,
} from '/js/constants/balancingConstants.js';
import GeneralFormError from '/js/components/global/GeneralFormError.vue';
import {onMounted, ref} from 'vue';
import {VillageExpGain} from 'resources/types/admin.js';
import {clearErrors} from '/js/services/errorService';
import axios from 'axios';

onMounted(async() => {
    const {data} = await axios.get('/admin/village-exp-gain');
    villageExpGain.value = data.data;
    loading.value = false;
});

const loading = ref(true);

const villageExpGain = ref<Array<VillageExpGain>>([]);

async function updateVillageExpGain() {
    clearErrors();
    const {data} = await axios.put('/admin/village-exp-gain', villageExpGain.value);
    villageExpGain.value = data.data.balancing;
}
</script>