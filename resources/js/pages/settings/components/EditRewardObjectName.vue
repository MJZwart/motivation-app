<template>
    <div v-if="rewardObj">
        <div class="form-group">
            <label for="username">{{ parsedLabelName }}</label>
            <span class="d-flex flex-row">
                <input
                    id="name" 
                    v-model="rewardObj.name" 
                    type="text" 
                    name="name"
                    :placeholder="$t('name')" 
                    :class="{ invalid: hasError('name') }"
                />
                <Tooltip :text="$t('random-name')" placement="top-left" class="dice-button">
                    <Icon icon="fa-solid:dice" @click="generateRandomName" />
                </Tooltip>
            </span>
            <BaseFormError name="name" />
        </div> 
        <FormControls
            :submit-text="$t('update-reward-name')"
            @submit="$emit('submit', rewardObj)"
            @cancel="$emit('close')"
        />
        <BaseFormError name="error" />
    </div>
</template>

<script setup lang="ts">
import type {Reward} from 'resources/types/reward';
import {ref, computed} from 'vue';
import {useI18n} from 'vue-i18n';
import FormControls from '/js/components/global/FormControls.vue';
import {deepCopy} from '/js/helpers/copy';
import {getRandomCharacterName, getRandomVillageName} from '/js/helpers/randomNames';
import {Icon} from '@iconify/vue';
import {hasError} from '/js/services/errorService';
const {t} = useI18n();

const props = defineProps<{form:  Reward}>();
defineEmits(['close', 'submit']);

const rewardObj = ref(deepCopy(props.form));

const parsedLabelName = computed(() => {
    if (rewardObj.value.rewardType == 'CHARACTER') {
        return t('character-name');
    } else if (rewardObj.value.rewardType == 'VILLAGE') {
        return t('village-name');
    } else {
        return null;
    }
});

function generateRandomName() {
    if (rewardObj.value.rewardType === 'CHARACTER') rewardObj.value.name = getRandomCharacterName();
    else if (rewardObj.value.rewardType === 'VILLAGE') rewardObj.value.name = getRandomVillageName();
}
</script>
