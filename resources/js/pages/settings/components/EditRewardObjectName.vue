<template>
    <div v-if="rewardObj">
        <div class="form-group">
            <label for="username">{{ parsedLabelName }}</label>
            <span class="d-flex flex-row">
                <input
                    id="name" 
                    type="text" 
                    name="name" 
                    :value="rewardObj.name"
                    :placeholder="$t('name')" 
                />
                <Tooltip :text="$t('random')" class="dice-button mr-2">
                    <Icon icon="fa-solid:dice" @click="generateRandomName" />
                </Tooltip>
            </span>
            <BaseFormError name="name" />
        </div> 
        <FormControls
            :submit-text="$t('update-reward-name')"
            @submit="$emit('submit', {rewardObj, type: form.type})"
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
const {t} = useI18n(); // use as global scope

const props = defineProps<{form: {rewardObj: Reward; type: string}}>();
defineEmits(['close', 'submit']);

const rewardObj = ref(deepCopy(props.form.rewardObj));

const parsedLabelName = computed(() => {
    if (props.form.type == 'CHARACTER') {
        return t('character-name');
    } else if (props.form.type == 'VILLAGE') {
        return t('village-name');
    } else {
        return null;
    }
});

function generateRandomName() {
    if (props.form.type === 'CHARACTER') rewardObj.value.name = getRandomCharacterName();
    else if (props.form.type === 'VILLAGE') rewardObj.value.name = getRandomVillageName();
}
</script>
