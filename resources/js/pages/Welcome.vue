<template>
    <div>
        <Modal :show="showFirstModal" :footer="false" :header="false">
            <template #header>
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
                <button class="block" @click="nextModal()">{{ $t('next') }}</button>
                <button class="block" variant="danger" @click="logout()">{{ $t('logout') }}</button>
            </div>
        </Modal>
        <Modal :show="showSecondModal" :footer="false" :header="false">
            <template #header>
                <div class="modal-header d-block">
                    <h4>{{ $t('little-more') }}</h4>
                    <p class="silent mb-0">{{ $t('pick-example-tasks') }}</p>
                </div>
            </template>
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
                <div class="d-flex">
                    <button class="mr-1" @click="startFirstModal()">{{ $t('go-back') }}</button>
                    <button @click="confirmSettings()">{{ $t('submit') }}</button>
                    <button class="ml-auto button-red" @click="logout()">{{ $t('logout') }}</button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {computed, ref, onMounted} from 'vue';
import {useMainStore} from '/js/store/store';
import {useTaskStore} from '/js/store/taskStore';
import {useUserStore} from '/js/store/userStore';
import {useI18n} from 'vue-i18n';
import type {Task} from 'resources/types/task';
import type {NewUser} from 'resources/types/user';
const {t} = useI18n();

const mainStore = useMainStore();
const taskStore = useTaskStore();
const userStore = useUserStore();

onMounted(async () => {
    mainStore.clearErrors();
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
/** @type Array<import('resources/types/task').Task> */
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
        mainStore.setErrorMessages({reward_object_name: ['No character name given.']});
        return false;
    } else if (user.value.rewardsType == 'VILLAGE' && !user.value.reward_object_name) {
        mainStore.setErrorMessages({reward_object_name: ['No village name given.']});
        return false;
    }
    mainStore.clearErrors();
    return true;
}
function logout() {
    userStore.logout();
}
</script>

<style>
.examples-slot {
    max-height: 500px;
    overflow-y: scroll;
}
</style>
