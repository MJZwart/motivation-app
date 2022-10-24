<template>
    <div v-if="achievementToEdit">
        <form @submit.prevent="updateAchievement">
            <SimpleInput  
                id="name" 
                v-model="achievementToEdit.name"
                type="text" 
                name="name" 
                :label="$t('achievement-name')"
                :placeholder="$t('name')"  />
            <SimpleInput  
                id="description" 
                v-model="achievementToEdit.description"
                type="text" 
                name="description" 
                :label="$t('achievement-description')"
                :placeholder="$t('description')"  />
            <div class="form-group">
                <label for="type">{{$t('trigger-type')}}</label>
                <select 
                    id="type" 
                    v-model="achievementToEdit.trigger_type"
                    value-field="trigger_type"
                    text-field="trigger_type">
                    <option v-for="(option, index) in achievementTriggers" :key="index" :value="option.type">
                        {{option.type}}
                    </option>
                </select>
                <BaseFormError name="trigger_type" /> 
            </div>
            <SimpleInput  
                id="trigger_amount" 
                v-model="achievementToEdit.trigger_amount"
                type="number" 
                name="trigger_amount" 
                :label="$t('trigger-amount')"
                :placeholder="$t('amount')"  />
            <div class="form-group">
                <label for="trigger-description">{{$t('trigger-description')}}</label>
                <p v-if="achievementToEdit.trigger_type" id="trigger-description">
                    {{parseAchievementTriggerDesc(achievementToEdit)}}
                </p>
            </div>
            <button type="submit" class="block">{{ $t('edit-achievement') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>


<script setup lang="ts">
import {onMounted, ref, PropType} from 'vue';
import {useAchievementStore} from '/js/store/achievementStore';
import {Achievement} from 'resources/types/achievement.js'
import {parseAchievementTriggerDesc} from '/js/services/achievementService';
import {ACHIEVEMENT_TRIGGERS} from '/js/constants/achievementsConstants';
const achievementStore = useAchievementStore();

const props = defineProps({
    achievement: {
        type: Object as PropType<Achievement>,
        required: true,
    },
});

const emit = defineEmits(['close']);

onMounted(() => {
    achievementToEdit.value = Object.assign({}, props.achievement);
});

const achievementToEdit = ref<Achievement | null>(null);
const achievementTriggers = ACHIEVEMENT_TRIGGERS;

async function updateAchievement() {
    if (!achievementToEdit.value) return;
    await achievementStore.editAchievement(achievementToEdit.value)
    close();
}
function close() {
    achievementToEdit.value = null,
    emit('close');
}
</script>
