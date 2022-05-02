<template>
    <div>
        <form @submit.prevent="submitAchievement">
            <Input 
                id="name" 
                v-model="achievement.name"
                type="text" 
                name="name" 
                :label="$t('achievement-name')"
                :placeholder="$t('name')"  />
            <Input 
                id="description" 
                v-model="achievement.description"
                type="text" 
                name="description" 
                :label="$t('achievement-description')"
                :placeholder="$t('description')"  />
            <div class="form-group">
                <label for="type">{{$t('trigger-type')}}</label>
                <select 
                    id="type" 
                    v-model="achievement.trigger_type"
                    value-field="trigger_type"
                    text-field="trigger_type">
                    <option v-for="(option, index) in achievementTriggers" :key="index" :value="option.trigger_type">
                        {{option.trigger_type}}
                    </option>
                </select>
                <BaseFormError name="trigger_type" /> 
            </div>
            <Input 
                id="trigger_amount" 
                v-model="achievement.trigger_amount"
                type="number" 
                name="trigger_amount" 
                :label="$t('trigger-amount')"
                :placeholder="$t('amount')"  />
            <div class="form-group">
                <label for="trigger-description">{{$t('trigger-description')}}</label>
                <p v-if="achievement.trigger_type" id="trigger-description">{{triggerDescription}}</p>
            </div>
            <button type="submit" class="block">{{ $t('create-new-achievement') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>


<script setup>
import {computed, ref} from 'vue';
import {useAchievementStore} from '/js/store/achievementStore';
const achievementStore = useAchievementStore();

const emit = defineEmits(['close']);

/** @type {import('resources/types/achievement').Achievement} */
const achievement = ref({
    trigger_amount: 0,
});

async function submitAchievement() {
    achievement.value.trigger_amount = parseInt(achievement.value.trigger_amount);
    await achievementStore.newAchievement(achievement.value)
    close();
}
function close() {
    achievement.value = {trigger_amount: 0},
    emit('close');
}
const achievementTriggers = computed(() => achievementStore.achievementTriggers);
/** Parses the achievement description from the type (eg Made {0} friends) and the amount */
const triggerDescription = computed(() => {
    const plural = achievement.value.trigger_amount > 1 ? 's' : '';
    let desc = achievementTriggers.value.find(item => item.trigger_type === achievement.value.trigger_type);
    desc = desc.trigger_description.replace('%d', achievement.value.trigger_amount);
    return desc.replace('%s', plural);
});
</script>
