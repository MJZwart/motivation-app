<template>
    <div class="w-50-flex center">
        <div v-if="showFirstModal">
            <h3 class="modal-title">{{ $t('welcome') }}</h3>
            <p class="silent mb-3">{{ $t('not-yet-done') }}</p>
            <div class="">
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
                <div v-if="user.rewardsType == 'CHARACTER' || user.rewardsType == 'VILLAGE'"
                     class="form-group">
                    <label for="username">{{ parsedLabelName }}</label>
                    <span class="d-flex flex-row">
                        <input
                            id="reward_object_name" 
                            type="text" 
                            name="reward_object_name" 
                            :value="user.reward_object_name"
                            :placeholder="parsedLabelName ?? ''" 
                        />
                        <Tooltip :text="$t('random-name')" class="dice-button mr-2">
                            <Icon icon="fa-solid:dice" @click="generateRandomName" />
                        </Tooltip>
                    </span>
                    <BaseFormError name="reward_object_name" />
                </div> 
                <small class="form-text text-muted mb-3">{{ $t('change-name-later') }}</small>
                <span class="d-flex">
                    <button class="ml-auto button-cancel mr-2" @click="logout()">{{ $t('logout') }}</button>
                    <button type="submit" @click="nextModal()">{{ $t('next') }}</button>
                </span>
            </div>
        </div>
        <div v-if="showSecondModal">
            <h3>{{ $t('little-more') }}</h3>
            <p class="silent mb-3">{{ $t('pick-example-tasks') }}</p>
            <div>
                <div class="form-group">
                    <label for="example-tasks">{{ $t('example-tasks') }}</label>
                    <div class="examples-slot">
                        <div v-for="(task, index) in exampleTasks" :key="index">
                            <!-- TODO the ID being a flat number may cause problems 
                                in future development and isn't specific enough -->
                            <input
                                :id="task.id.toString()"
                                v-model="user.tasks"
                                type="checkbox"
                                name="example-tasks"
                                :value="task.id"
                            />
                            <label class="task-title label-override" :for="task.id.toString()">{{ task.name }}</label>
                            <small class="form-text text-muted task-description label-override">
                                {{ task.description }}
                            </small>
                            <BaseFormError name="public-checkbox" />
                        </div>
                    </div>
                </div>
                <span class="d-flex">
                    <button class="mr-2 button-cancel" @click="startFirstModal()">{{ $t('go-back') }}</button>
                    <button class="ml-auto mr-2 button-cancel" @click="logout()">{{ $t('logout') }}</button>
                    <button type="submit" class="mr-2" @click="confirmSettings()">{{ $t('submit') }}</button>
                </span>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, ref, onMounted} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import {useUserStore} from '/js/store/userStore';
import {useI18n} from 'vue-i18n';
import type {Task} from 'resources/types/task';
import type {NewUser} from 'resources/types/user';
import {clearErrors, setErrorMessages} from '/js/services/errorService';
import {getRandomCharacterName, getRandomVillageName} from '/js/helpers/randomNames';
const {t} = useI18n();

const taskStore = useTaskStore();
const userStore = useUserStore();

onMounted(async () => {
    clearErrors();
    exampleTasks.value = await taskStore.fetchExampleTasks();
    startFirstModal();
});

const user = ref<NewUser>({
    rewardsType: 'NONE',
    tasks: [],
    reward_object_name: null,
});
const showFirstModal = ref(false);
const showSecondModal = ref(false);
const exampleTasks = ref<Task[]>([]);

const parsedLabelName = computed(() => {
    if (user.value.rewardsType == 'CHARACTER') {
        return t('character-name');
    } else if (user.value.rewardsType == 'VILLAGE') {
        return t('village-name');
    } else {
        return null;
    }
});

function startFirstModal() {
    showFirstModal.value = true;
    showSecondModal.value = false;
}
function nextModal() {
    if (checkInput()) {
        showFirstModal.value = false;
        showSecondModal.value = true;
    }
}
function confirmSettings() {
    userStore.confirmRegister(user.value);
}
function checkInput() {
    if (user.value.rewardsType == 'CHARACTER' && !user.value.reward_object_name) {
        setErrorMessages({reward_object_name: ['No character name given.']});
        return false;
    } else if (user.value.rewardsType == 'VILLAGE' && !user.value.reward_object_name) {
        setErrorMessages({reward_object_name: ['No village name given.']});
        return false;
    }
    clearErrors();
    return true;
}
function logout() {
    userStore.logout();
}
function generateRandomName() {
    if (user.value.rewardsType === 'CHARACTER') user.value.reward_object_name = getRandomCharacterName();
    else if (user.value.rewardsType === 'VILLAGE') user.value.reward_object_name = getRandomVillageName();
}
</script>

<style>
.examples-slot {
    max-height: 500px;
    overflow-y: scroll;
}
</style>
