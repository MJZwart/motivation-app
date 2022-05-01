<template>
    <div v-if="achievementToEdit">
        <form @submit.prevent="updateAchievement">
            <div class="form-group">
                <label for="name">{{$t('achievement-name')}}</label>
                <input  
                    id="name" 
                    v-model="achievementToEdit.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <BaseFormError name="name" /> 
            </div>
            <div class="form-group">
                <label for="description">{{$t('achievement-description')}}</label>
                <input  
                    id="description" 
                    v-model="achievementToEdit.description"
                    type="text" 
                    name="description" 
                    :placeholder="$t('description')"  />
                <BaseFormError name="description" /> 
            </div>
            <div class="form-group">
                <label for="type">{{$t('trigger-type')}}</label>
                <select 
                    id="type" 
                    v-model="achievementToEdit.trigger_type"
                    value-field="trigger_type"
                    text-field="trigger_type">
                    <option v-for="(option, index) in achievementTriggers" :key="index" :value="option.trigger_type">
                        {{option.trigger_type}}
                    </option>
                </select>
                <BaseFormError name="trigger_type" /> 
            </div>
            <div class="form-group">
                <label for="trigger_amount">{{$t('trigger-amount')}}</label>
                <input  
                    id="trigger_amount" 
                    v-model="achievementToEdit.trigger_amount"
                    type="number" 
                    name="trigger_amount" 
                    :placeholder="$t('amount')"  />
                <BaseFormError name="trigger_amount" /> 
            </div>
            <div class="form-group">
                <label for="trigger-description">{{$t('trigger-description')}}</label>
                <p v-if="achievementToEdit.trigger_type" id="trigger-description">{{triggerDescription}}</p>
            </div>
            <button type="submit" class="block">{{ $t('edit-achievement') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>


<script setup>

import {onMounted, computed, ref} from 'vue';
import {useAchievementStore} from '/js/store/achievementStore';
const achievementStore = useAchievementStore();

const props = defineProps({
    achievement: {
        /** @type {import('resources/types/achievement').Achievement} */
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close']);

onMounted(() => {
    achievementToEdit.value = Object.assign({}, props.achievement);
});

/** @type {import('resources/types/achievement').Achievement} */
const achievementToEdit = ref({});
const achievementTriggers = computed(() => achievementStore.achievementTriggers);

async function updateAchievement() {
    await achievementStore.editAchievement(achievementToEdit.value)
    close();
}
function close() {
    achievementToEdit.value = {},
    emit('close');
}

/** Parses the achievement description from the type (eg Made {0} friends) and the amount */
const triggerDescription = computed(() => {
    const plural = props.achievement.trigger_amount > 1 ? 's' : '';
    let desc = achievementTriggers.value.find(item => item.trigger_type === props.achievement.trigger_type);
    desc = desc.trigger_description.replace('%d', props.achievement.trigger_amount);
    return desc.replace('%s', plural);
});
</script>
