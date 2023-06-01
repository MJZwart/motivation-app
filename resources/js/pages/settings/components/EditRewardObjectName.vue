<template>
    <div v-if="rewardObj">
        <SimpleInput
            id="name"
            v-model="editedRewardObj.name"
            type="text"
            name="name"
            :label="parsedLabelName"
            :placeholder="$t('name')"
        />    
        <FormControls
            :submit-text="$t('update-reward-name')"
            @submit="updateRewardObj"
            @cancel="$emit('close')"
        />
        <BaseFormError name="error" />
    </div>
</template>

<script setup lang="ts">
import type {Reward} from 'resources/types/reward';
import {ref, computed} from 'vue';
import {useI18n} from 'vue-i18n';
import {useRewardStore} from '/js/store/rewardStore';
import FormControls from '/js/components/global/FormControls.vue';
const {t} = useI18n(); // use as global scope
const rewardStore = useRewardStore();

const props = defineProps<{rewardObj: Reward; type: string}>();
const emit = defineEmits(['close']);

const editedRewardObj = ref<Reward>(Object.assign({}, props.rewardObj));

async function updateRewardObj() {
    editedRewardObj.value.rewardType = props.type;
    await rewardStore.updateRewardObjName(editedRewardObj.value);
    close();
}
function close() {
    emit('close');
}

const parsedLabelName = computed(() => {
    if (props.type == 'CHARACTER') {
        return t('character-name');
    } else if (props.type == 'VILLAGE') {
        return t('village-name');
    } else {
        return null;
    }
});
</script>
