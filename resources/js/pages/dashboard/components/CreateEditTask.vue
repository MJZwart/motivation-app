<template>
    <div>
        <SimpleInput
            id="name"
            v-model="activeTask.name"
            type="text"
            name="name"
            :label="$t('task-name')"
            :placeholder="$t('name')" />
        <SimpleInput
            id="description"
            v-model="activeTask.description"
            type="text"
            name="description"
            :label="$t('description-optional')"
            :placeholder="$t('description')" />
        <div class="form-group">
            <label for="type">{{ $t('type') }}</label>
            <select id="type" v-model="activeTask.type" name="type">
                <option v-for="(type, index) in taskTypes" :key="index" :value="type.value">{{ $t(type.text) }}</option>
            </select>
            <BaseFormError name="type" />
        </div>
        <SimpleInput
            id="difficulty"
            v-model="activeTask.difficulty"
            type="range"
            name="difficulty"
            :label="$t('difficulty') + ': ' + activeTask.difficulty + '/5'"
            min="1"
            max="5"
        />
        <div class="form-group">
            <label for="repeatable">{{ $t('repeatable') }}</label>
            <select id="repeatable" v-model="activeTask.repeatable" name="repeatable">
                <option v-for="(option, index) in repeatables" :key="index" :value="option.value">
                    {{ $t(option.text) }}
                </option>
            </select>
            <BaseFormError name="repeatable" />
        </div>
        <div class="form-group">
            <p v-if="form.taskList">{{ $t('task-list') }}: {{ form.taskList.name }}</p>
            <p v-if="form.superTask">{{ $t('subtask-of') }}: {{ form.superTask.name }}</p>
            <div v-if="templates.length">{{$t('import-from-templates')}}
                <select id="templates" class="template-select" @change="selectTemplate($event)">
                    <option :value="null"/>
                    <option v-for="(template, index) in templates" :key="index" :value="index">
                        {{ `${template.name} - ${$t(template.type)} - ${$t('difficulty')}: ${template.difficulty}/5` }}
                    </option>
                </select>
            </div>
        </div>
        <SubmitButton id="create-new-task-button" class="block" @click="$emit('submit', {task: activeTask})">
            {{ $t('create-new-task') }}
        </SubmitButton>
        <button type="button" class="block button-cancel" @click="emit('close')">{{ $t('cancel') }}</button>
    </div>
</template>

<script setup lang="ts">
import type {NewTask, TaskList, Task} from 'resources/types/task';
import {TASK_TYPES, REPEATABLES} from '/js/constants/taskConstants';
import {onMounted, ref} from 'vue';
import {templates} from '../taskService';

const taskTypes = TASK_TYPES;
const repeatables = REPEATABLES;

const props = defineProps<{
    form: {
        task: Task | NewTask, 
        taskList: TaskList, 
        superTask?: Task | null
    }
}>();

const emit = defineEmits<{
    (event: 'close'): void,
    (event: 'submit', {task}: {task: NewTask | Task}): void,
}>();

const activeTask = ref({...props.form.task});

onMounted(async() => {
    if (props.form.superTask && !activeTask.value.super_task_id) activeTask.value.super_task_id = props.form.superTask.id;
});

function selectTemplate(event: Event) {
    if (!event || !event.target || !(event.target as HTMLSelectElement).value) return;
    const template = templates.value[(event.target as HTMLSelectElement).value];

    activeTask.value.name = template.name;
    activeTask.value.description = template.description ?? '';
    activeTask.value.difficulty = template.difficulty;
    activeTask.value.type = template.type;
}
</script>