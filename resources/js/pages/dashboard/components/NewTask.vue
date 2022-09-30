<template>
    <div>
        <form @submit.prevent="submitTask">
            <SimpleInput
                id="name"
                v-model="task.name"
                type="text"
                name="name"
                :label="$t('task-name')"
                :placeholder="$t('name')"
            />
            <SimpleInput
                id="description"
                v-model="task.description"
                type="text"
                name="description"
                :label="$t('description-optional')"
                :placeholder="$t('description')"
            />
            <div class="form-group">
                <label for="type">{{ $t('type') }}</label>
                <select id="type" v-model="task.type" name="type">
                    <option v-for="(type, index) in taskTypes" :key="index" :value="type.value">{{ type.text }}</option>
                </select>
                <BaseFormError name="type" />
            </div>
            <SimpleInput
                id="difficulty"
                v-model="task.difficulty"
                type="range"
                name="difficulty"
                :label="$t('difficulty') + ': ' + task.difficulty + '/5'"
                min="1"
                max="5"
            />
            <div class="form-group">
                <label for="repeatable">{{ $t('repeatable') }}</label>
                <select id="repeatable" v-model="task.repeatable" name="repeatable">
                    <option v-for="(option, index) in repeatables" :key="index" :value="option.value">
                        {{ option.text }}
                    </option>
                </select>
                <BaseFormError name="repeatable" />
            </div>
            <div class="form-group">
                <p v-if="taskList">{{ $t('task-list') }}: {{ taskList.name }}</p>
                <p v-if="superTask">{{ $t('subtask-of') }}: {{ superTask.name }}</p>
            </div>
            <button id="create-new-task-button" type="submit" class="block">{{ $t('create-new-task') }}</button>
            <button type="button" class="block" @click="emit('close')">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>

<script setup lang="ts">
import {TASK_TYPES, REPEATABLES} from '/js/constants/taskConstants';
import {ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import {NewTask, Task, TaskList} from 'resources/types/task';

const taskTypes = TASK_TYPES;
const repeatables = REPEATABLES;

const props = defineProps<{taskList: TaskList; superTask?: Task | null}>();
const emit = defineEmits(['close']);

const task = ref<NewTask>({
    name: '',
    difficulty: 3,
    type: 'GENERIC',
    repeatable: 'NONE',
    task_list_id: props.taskList.id,
});

const taskStore = useTaskStore();
async function submitTask() {
    task.value.super_task_id = props.superTask ? props.superTask.id : null;
    await taskStore.storeTask(task.value);
    emit('close');
}
</script>
