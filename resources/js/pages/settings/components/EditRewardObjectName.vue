<template>
    <div v-if="rewardObj">
        <form @submit.prevent="updateRewardObj">
            <div class="form-group">
                <label for="name">{{parsedLabelName}}</label>
                <input 
                    id="name" 
                    v-model="editedRewardObj.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <BaseFormError name="name" /> 
            </div>
            <button type="submit" class="block">{{ $t('update-reward-name') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
            <BaseFormError name="error" /> 
        </form>
    </div>
</template>


<script setup>

import {onMounted, ref, computed} from 'vue';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope
import {useRewardStore} from '/js/store/rewardStore';
const rewardStore = useRewardStore();

const props = defineProps({
    rewardObj: {
        type: Object,
        required: true,
    },
    type: {
        type: String,
        required: true,
    },
});
const emit = defineEmits(['close']);

onMounted(() => editedRewardObj.value = props.rewardObj ? Object.assign({}, props.rewardObj) : {});

const editedRewardObj = ref({});

async function updateRewardObj() {
    editedRewardObj.value.type = props.type;
    await rewardStore.updateRewardObjName(editedRewardObj.value);
    close();
}
function close() {
    editedRewardObj.value = {},
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
