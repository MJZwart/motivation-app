<template>
    <div class="w-50-flex center">
        <div v-if="showFirstModal">
            <h3 class="modal-title">{{ $t('welcome') }}</h3>
            <div class="">
                <div class="form-group">
                    <small class="form-text text-muted mb-2">{{ $t('which-reward-type') }}</small>
                    <div>
                        <input id="NONE" v-model="user.rewardsType" name="rewards-type" type="radio" value="NONE" />
                        <label for="NONE" class="option-label">{{ $t('no-rewards') }}</label>
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
                <div v-if="user.rewardsType == 'VILLAGE'"
                     class="form-group">
                    <label for="username">{{ $t('village-name') }}</label>
                    <span class="d-flex flex-row">
                        <input
                            id="village_name" 
                            v-model="user.village_name" 
                            type="text" 
                            name="village_name"
                            :placeholder="$t('village-name') ?? ''" 
                            :class="{ invalid: hasError('village_name') }"
                        />
                        <Tooltip :text="$t('random-name')" class="dice-button mr-2">
                            <Icon icon="fa-solid:dice" @click="generateRandomName" />
                        </Tooltip>
                    </span>
                    <BaseFormError name="village_name" />
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
import {ref, onMounted} from 'vue';
import type {Task} from 'resources/types/task';
import type {NewUser} from 'resources/types/user';
import {clearErrors, hasError, setErrorMessages} from '/js/services/errorService';
import {getRandomVillageName} from '/js/helpers/randomNames';
import {logout, setUser} from '/js/services/userService';
import axios from 'axios';
import router from '/js/router/router';

onMounted(async () => {
    clearErrors();
    const {data} = await axios.get('/examples/tasks');
    exampleTasks.value = data.data;
    startFirstModal();
});

const user = ref<NewUser>({
    rewardsType: 'NONE',
    tasks: [],
    village_name: null,
});
const showFirstModal = ref(false);
const showSecondModal = ref(false);
const exampleTasks = ref<Task[]>([]);

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
async function confirmSettings() {
    const {data} = await axios.post('/register/confirm', user.value);
    setUser(data.data.user);
    router.push('/');
}
function checkInput() {
    if (user.value.rewardsType == 'VILLAGE' && !user.value.village_name) {
        setErrorMessages({village_name: ['No village name given.']});
        return false;
    }
    clearErrors();
    return true;
}
function generateRandomName() {
    user.value.village_name = getRandomVillageName();
}
</script>

<style>
.examples-slot {
    max-height: 500px;
    overflow-y: scroll;
}
</style>
