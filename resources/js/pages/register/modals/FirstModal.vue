<template>
    <div>
        <template>
            <div class="modal-header d-block">
                <h5 class="modal-title">{{ $t('welcome') }}</h5>
                <p class="silent mb-0">{{ $t('not-yet-done') }}</p>
            </div>
        </template>
        <div>
            <div class="form-group">
                <label for="rewards-type">{{ $t('rewards-type') }}</label>
                <small class="form-text text-muted mb-2">{{ $t('which-reward-type') }}</small>
                <div>
                    <input id="NONE" v-model="user.rewardsType" name="rewards-type" type="radio" value="NONE" />
                    <label for="NONE" class="option-label">{{ $t('no-rewards') }}</label>
                </div>
                <div>
                    <input
                        id="CHARACTER"
                        v-model="user.rewardsType"
                        name="rewards-type"
                        type="radio"
                        value="CHARACTER"
                    />
                    <label for="CHARACTER" class="option-label">{{ $t('character-reward') }}</label>
                </div>
                <div>
                    <input
                        id="VILLAGE"
                        v-model="user.rewardsType"
                        name="rewards-type"
                        type="radio"
                        value="VILLAGE"
                    />
                    <label for="VILLAGE" class="option-label">{{ $t('village-reward') }}</label>
                </div>
                <BaseFormError name="rewards-type" />
            </div>
            <SimpleInput
                v-if="user.rewardsType == 'CHARACTER' || user.rewardsType == 'VILLAGE'"
                id="reward_object_name"
                v-model="user.reward_object_name"
                type="text"
                name="reward_object_name"
                :label="parsedLabelName"
                :placeholder="parsedLabelName"
            />
            <small class="form-text text-muted mb-3">{{ $t('change-name-later') }}</small>
            <button class="block" @click="$emit('next', user)">{{ $t('next') }}</button>
            <button class="block button-cancel" @click="$emit('logout')">{{ $t('logout') }}</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, ref} from 'vue';
import {deepCopy} from '/js/helpers/copy';
import {NewUser} from 'resources/types/user';
import {useI18n} from 'vue-i18n';
const {t} = useI18n();


const props = defineProps<{newUser: NewUser}>();
defineEmits(['next', 'logout', 'close']);

const user = ref(deepCopy(props.newUser));

const parsedLabelName = computed(() => {
    if (user.value.rewardsType == 'CHARACTER') {
        return t('character-name');
    } else if (user.value.rewardsType == 'VILLAGE') {
        return t('village-name');
    } else {
        return null;
    }
});
</script>