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
                <base-form-error name="name" /> 
            </div>
            <button type="submit" class="block">{{ $t('update-reward-name') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
            <base-form-error name="error" /> 
        </form>
    </div>
</template>


<script setup>
import BaseFormError from '../BaseFormError.vue';
import {shallowRef, onMounted, ref, computed} from 'vue';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope
import {useRewardStore} from '/js/store/rewardStore';

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

onMounted(() => props.rewardObj ? editedRewardObj.value = shallowRef(props.rewardObj) : editedRewardObj.value = {});

const editedRewardObj = ref({});

async function updateRewardObj() {
    editedRewardObj.value.type = props.type;
    await useRewardStore.updateRewardObjName(editedRewardObj);
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
