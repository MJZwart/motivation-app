<template>
    <div>
        <form @submit.prevent="submitTask">
            <Input 
                id="name" 
                v-model="task.name"
                type="text" 
                name="name" 
                :label="$t('task-name')"
                :placeholder="$t('name')" />
            <Input  
                id="description" 
                v-model="task.description"
                type="text" 
                name="description" 
                :label="$t('description-optional')"
                :placeholder="$t('description')"  />
            <div class="form-group">
                <label for="type">{{$t('type')}}</label>
                <select
                    id="type"
                    v-model="task.type"
                    name="type">
                    <option v-for="(type, index) in taskTypes" :key="index" :value="type.value">{{type.text}}</option>
                </select>
                <base-form-error name="type" /> 
            </div>
            <Input 
                id="difficulty"
                v-model="task.difficulty"
                type="range"
                name="difficulty"
                :label="$t('difficulty')+': '+task.difficulty+'/5'"
                min="1"
                max="5" />
            <div class="form-group">
                <label for="repeatable">{{$t('repeatable')}}</label>
                <select
                    id="repeatable"
                    v-model="task.repeatable"
                    name="repeatable">
                    <option v-for="(option, index) in repeatables" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <base-form-error name="repeatable" /> 
            </div>
            <div class="form-group">
                <p v-if="taskList">{{ $t('task-list') }}: {{taskList.name}}</p>
                <p v-if="superTask">{{ $t('subtask-of') }}: {{superTask.name}}</p>
            </div>
            <button type="submit" class="block">{{ $t('create-new-task') }}</button>
            <button type="button" class="block" @click="emit('close')">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>


<script setup>
import {TASK_TYPES, REPEATABLES} from '/js/constants/taskConstants';
import {reactive} from 'vue';
import {useTaskStore} from '/js/store/taskStore';

const taskTypes = TASK_TYPES;
const repeatables = REPEATABLES;

const props = defineProps({
    taskList: {
        /** @type {import('../../../types/task').TaskList} */
        type: Object,
        required: true,
    },
    superTask: {
        /** @type {import('../../../types/task').Task} */
        type: Object,
        required: false,
    },
});
const emit = defineEmits(['close'])

const task = reactive({
    difficulty: 3,
    type: 'GENERIC',
    repeatable: 'NONE',
});

const taskStore = useTaskStore();
async function submitTask() {
    task.super_task_id = props.superTask ? props.superTask.id : null;
    task.task_list_id = props.taskList.id || null;
    await taskStore.storeTask(task);
    emit('close');
}
</script>