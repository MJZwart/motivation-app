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
        <div v-if="activeTask.repeatable === 'WEEKLY'" class="form-group">
            <label for="repeatable_reset_day">{{ $t('reset-day') }}</label>
            <select id="repeatable_reset_day" v-model="activeTask.repeatable_reset_day" name="repeatable_reset_day">
                <option v-for="(option, index) in days" :key="index" :value="option.value">
                    {{ $t(option.text) }}
                </option>
            </select>
            <BaseFormError name="repeatable_reset_day" />
        </div>
        <div v-if="activeTask.repeatable === 'WEEKLY_MULTIPLE'" class="form-group">
            <label for="reset_days">{{ $t('reset-days') }}</label>
            <Multiselect
                v-model="activeTask.repeatable_reset_days"
                mode="tags"
                :options="days"
                valueProp="value"
                label="text"
                :placeholder="$t('select-reset-days')"
                openDirection="top"
            />
            <BaseFormError name="repeatable_reset_days" />
        </div>
        <div class="form-group">
            <p v-if="form.taskList">{{ $t('task-list') }}: {{ form.taskList.name }}</p>
            <p v-if="form.superTask">{{ $t('subtask-of') }}: {{ form.superTask.name }}</p>
            <div v-if="templates.length">{{$t('import-from-templates')}}
                <select id="templates" class="template-select" @change="selectTemplate($event)">
                    <option :value="null" />
                    <option v-for="(template, index) in templates" :key="index" :value="index">
                        {{ `${template.name} - ${$t(template.type)} - ${$t('difficulty')}: ${template.difficulty}/5` }}
                    </option>
                </select>
            </div>
        </div>
        <FormControls
            :submit-text="'id' in form.task ? $t('edit-task') : $t('create-new-task')"
            @submit="$emit('submit', {task: activeTask})"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import type {NewTask, TaskList, Task} from 'resources/types/task';
import {TASK_TYPES, REPEATABLES, DAYS} from '/js/constants/taskConstants';
import {onMounted, ref, watchEffect} from 'vue';
import {templates} from '../taskService';
import FormControls from '/js/components/global/FormControls.vue';
import Multiselect from '@vueform/multiselect';

const taskTypes = TASK_TYPES;
const repeatables = REPEATABLES;
const days = DAYS;

const props = defineProps<{
    form: {
        task: Task | NewTask, 
        taskList: TaskList, 
        superTask?: Task | null
    }
}>();

defineEmits<{
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

watchEffect(() => {
    if (activeTask.value.repeatable === 'WEEKLY' && !activeTask.value.repeatable_reset_day)
        activeTask.value.repeatable_reset_day = days[0].value;
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>