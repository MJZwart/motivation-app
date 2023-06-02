<template>
    <div v-if="rewardObj">
        <SimpleInput
            id="name"
            v-model="rewardObj.name"
            type="text"
            name="name"
            :label="parsedLabelName"
            :placeholder="$t('name')"
        />    
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
</script>
