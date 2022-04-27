<template>
    <div>
        <BModal :show="showFirstModal" :footer="false" :header="false">
            <template #header>
                <div class="modal-header d-block">
                    <h5 class="modal-title">{{ $t('welcome') }}</h5>
                    <p class="silent mb-0">{{ $t('not-yet-done') }}</p>
                </div>
            </template>
            <div>
                <div class="form-group">
                    <label for="rewards-type">{{$t('rewards-type')}}</label>
                    <small class="form-text text-muted mb-2">{{ $t('which-reward-type') }}</small>
                    <div>
                        <input 
                            id="NONE" 
                            v-model="user.rewardsType" 
                            name="rewards-type" 
                            type="radio" 
                            value="NONE" />
                        <label for="NONE" class="option-label">{{ $t('no-rewards') }}</label>
                    </div>
                    <div>
                        <input 
                            id="CHARACTER" 
                            v-model="user.rewardsType" 
                            name="rewards-type" 
                            type="radio" 
                            value="CHARACTER" />
                        <label for="CHARACTER" class="option-label">{{ $t('character-reward') }}</label>
                    </div>
                    <div>
                        <input 
                            id="VILLAGE" 
                            v-model="user.rewardsType" 
                            name="rewards-type" 
                            type="radio" 
                            value="VILLAGE" />
                        <label for="VILLAGE" class="option-label">{{ $t('village-reward') }}</label>
                    </div>
                    <BaseFormError name="rewards-type" /> 
                </div>
                <div v-if="user.rewardsType == 'CHARACTER' || user.rewardsType == 'VILLAGE'" class="form-group">
                    <label for="reward_object_name">{{parsedLabelName}}</label>
                    <input 
                        id="reward_object_name" 
                        v-model="user.reward_object_name"
                        type="text" 
                        name="reward_object_name" 
                        :placeholder="parsedLabelName"  />
                    <small class="form-text text-muted">{{$t('change-name-later')}}</small>
                    <BaseFormError name="reward_object_name" /> 
                </div>
                <button class="block" @click="nextModal()">{{ $t('next') }}</button>
                <button class="block" variant="danger" @click="logout()">{{ $t('logout')}}</button>
            </div>
        </BModal>
        <BModal :show="showSecondModal" :footer="false" :header="false">
            <template #header>
                <div class="modal-header d-block">
                    <h4>{{ $t('little-more') }}</h4>
                    <p class="silent mb-0">{{ $t('pick-example-tasks') }}</p>
                </div>
            </template>
            <div>
                <div class="form-group">
                    <label for="example-tasks">{{$t('example-tasks')}}</label>
                    <div class="examples-slot">
                        <div v-for="(task, index) in exampleTasks" :key="index">
                            <input
                                :id="task.id"
                                v-model="user.tasks"
                                type="checkbox"
                                name="example-tasks"
                                :value="task.id" />
                            <label class="task-title label-override" :for="task.id">{{task.name}}</label>
                            <small class="form-text text-muted task-description label-override">{{task.description}}</small>
                            <base-form-error name="public-checkbox" /> 
                        </div>
                    </div>
                    
                </div>
                <div class="d-flex">
                    <button class="mr-1" @click="startFirstModal()">{{ $t('go-back') }}</button>
                    <button @click="confirmSettings()">{{ $t('submit') }}</button>
                    <button class="ml-auto button-red" @click="logout()">{{ $t('logout')}}</button>
                </div>
            </div>
        </BModal>
    </div>
</template>

<script setup>
import BaseFormError from '../components/BaseFormError.vue';
import {computed, reactive, ref, onMounted} from 'vue';
import BModal from '../components/bootstrap/BModal.vue';
import {useMainStore} from '@/store/store';
import {useTaskStore} from '@/store/taskStore';
import {useUserStore} from '@/store/userStore';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope

const mainStore = useMainStore();
const taskStore = useTaskStore();
const userStore = useUserStore();

onMounted(() => {
    mainStore.clearErrors();
    taskStore.fetchExampleTasks();
    startFirstModal();
});

const user = reactive({
    rewardsType: 'NONE',
    tasks: [],
    reward_object_name: null,
});
const showFirstModal = ref(false);
const showSecondModal = ref(false);
const exampleTasks = computed (() => taskStore.exampleTasks);

const parsedLabelName = computed(() => {
    if (user.rewardsType == 'CHARACTER') {
        return t('character-name');
    } else if (user.rewardsType == 'VILLAGE') {
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
    userStore.confirmRegister(user);
}
function checkInput() {
    if (user.rewardsType == 'CHARACTER' && !user.reward_object_name) {
        mainStore.setErrorMessages({'reward_object_name': ['No character name given.']});
        return false;
    } else if (user.rewardsType == 'VILLAGE' && !user.reward_object_name) {
        mainStore.setErrorMessages({'reward_object_name': ['No village name given.']});
    } else {
        mainStore.clearErrors();
        return true;
    }
}
function logout() {
    userStore.logout();
}
</script>

<style>
.examples-slot{
    max-height:500px;
    overflow-y:scroll;
}
</style>