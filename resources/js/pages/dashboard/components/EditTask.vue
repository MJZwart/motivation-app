<template>
    <div v-if="editedTask">
        <form @submit.prevent="updateTask">
            <Input 
                id="name" 
                v-model="editedTask.name"
                type="text" 
                name="name" 
                :label="$t('task-name')"
                :placeholder="$t('name')"  />
            <Input 
                id="description" 
                v-model="editedTask.description"
                type="text" 
                name="description" 
                :label="$t('description-optional')"
                :placeholder="$t('description')"  />
            <div class="form-group">
                <label for="type">{{$t('type')}}</label>
                <select
                    id="type"
                    v-model="editedTask.type"
                    name="type">
                    <option v-for="(option, index) in taskTypes" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <BaseFormError name="type" /> 
            </div>
            <div class="form-group">
                <label for="difficulty">{{'Difficulty: '+editedTask.difficulty+'/5'}}</label>
                <input 
                    id="difficulty"
                    v-model="editedTask.difficulty"
                    type="range"
                    name="difficulty"
                    min="1"
                    max="5" />
                <BaseFormError name="difficulty" /> 
            </div>
            <div class="form-group">
                <label for="repeatable">{{$t('repeatable')}}</label>
                <select
                    id="repeatable"
                    v-model="editedTask.repeatable"
                    name="repeatable">
                    <option v-for="(option, index) in repeatables" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <BaseFormError name="repeatable" /> 
            </div>
            <!-- <div class="form-group">
                <p v-if="editedTask.taskList">{{ $t('task-list') }}: {{editedTask.taskList}}</p>
                <p v-if="editedTask.superTask">{{ $t('subtask-of') }}: {{editedTask.superTask}}</p>
            </div> -->
            <button type="submit" class="block">{{ $t('edit-task') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>


<script setup lang="ts">
import {TASK_TYPES, REPEATABLES} from '/js/constants/taskConstants';
import {ref, onMounted} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import type {Task} from 'resources/types/task';

const editedTask = ref<Task | null>(null);
const taskTypes = TASK_TYPES;
const repeatables = REPEATABLES;

const props = defineProps<{task: Task}>();
const emit = defineEmits(['close']);
onMounted(() => {
    editedTask.value = props.task ? Object.assign({}, props.task) : null;
});

const taskStore = useTaskStore();

async function updateTask() {
    if (!editedTask.value) return;
    await taskStore.updateTask(editedTask.value);
    close();
}
function close() {
    emit('close');
}
</script>
