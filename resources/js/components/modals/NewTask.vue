<template>
    <div>
        <form @submit.prevent="submitTask">
            <div class="form-group">
                <label for="name">{{$t('task-name')}}</label>
                <input 
                    id="name" 
                    v-model="task.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')" />
                <base-form-error name="name" /> 
            </div>
            <div class="form-group">
                <label for="description">{{$t('description-optional')}}</label>
                <input  
                    id="description" 
                    v-model="task.description"
                    type="text" 
                    name="description" 
                    :placeholder="$t('description')"  />
            </div>
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
            <div class="form-group">
                <label for="difficulty">{{$t('difficulty')+': '+task.difficulty+'/5'}}</label>
                <input 
                    id="difficulty"
                    v-model="task.difficulty"
                    type="range"
                    name="difficulty"
                    min="1"
                    max="5" />
                <base-form-error name="difficulty" /> 
            </div>
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
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import {TASK_TYPES, REPEATABLES} from '../../constants/taskConstants';

export default {
    components: {
        BaseFormError,
    },
    props: {
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
    },
    data() {
        return {
            /** @type {import('../../../types/task').Task} */
            task: {
                difficulty: 3,
                type: 'GENERIC',
                repeatable: 'NONE',
            },
            taskTypes: TASK_TYPES,
            repeatables: REPEATABLES,
        }
    },
    methods: {
        submitTask() {
            this.task.super_task_id = this.superTask ? this.superTask.id : null;
            this.task.task_list_id = this.taskList.id || null;
            var self = this;
            this.$store.dispatch('task/storeTask', this.task).then(function() {
                self.close();
            });
        },
        close() {
            this.$emit('close');
        },
    },
}
</script>
