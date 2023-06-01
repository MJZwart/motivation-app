<template>
    <div>
        <SimpleInput
            id="name"
            v-model="achievement.name"
            type="text"
            name="name"
            :label="$t('achievement-name')"
            :placeholder="$t('name')"
        />
        <SimpleInput
            id="description"
            v-model="achievement.description"
            type="text"
            name="description"
            :label="$t('achievement-description')"
            :placeholder="$t('description')"
        />
        <div class="form-group">
            <label for="type">{{ $t('trigger-type') }}</label>
            <select
                id="type"
                v-model="achievement.trigger_type"
                value-field="trigger_type"
                text-field="trigger_type"
            >
                <option v-for="(option, index) in achievementTriggers" :key="index" :value="option.type">
                    {{ option.type }}
                </option>
            </select>
            <BaseFormError name="trigger_type" />
        </div>
        <SimpleInput
            id="trigger_amount"
            v-model="achievement.trigger_amount"
            type="number"
            name="trigger_amount"
            :label="$t('trigger-amount')"
            :placeholder="$t('amount')"
        />
        <div class="form-group">
            <label for="trigger-description">{{ $t('trigger-description') }}</label>
            <p v-if="achievement.trigger_type" id="trigger-description">{{ parseAchievementTriggerDesc(achievement) }}</p>
        </div>
        <FormControls
            :submit-text="'id' in achievement ? $t('edit-achievement') : $t('create-new-achievement')"            
            @submit="submitAchievement"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {Achievement, NewAchievement} from 'resources/types/achievement.js';
import {ACHIEVEMENT_TRIGGERS} from '/js/constants/achievementsConstants';
import {parseAchievementTriggerDesc} from '/js/services/achievementService';
import {deepCopy} from '/js/helpers/copy';
import FormControls from '/js/components/global/FormControls.vue';

const emit = defineEmits(['close', 'submit']);
const props = defineProps<{form: NewAchievement | Achievement}>();

const achievement = ref<NewAchievement>(deepCopy(props.form));
const achievementTriggers = ACHIEVEMENT_TRIGGERS;

async function submitAchievement() {
    achievement.value.trigger_amount = Number(achievement.value.trigger_amount);
    emit('submit', achievement.value);
}
</script>
