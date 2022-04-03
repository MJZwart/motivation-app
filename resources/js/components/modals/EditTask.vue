<template>
    <div v-if="editedTask">
        <b-form @submit.prevent="updateTask">
            <div class="form-group">
                <label for="name">{{$t('task-name')}}</label>
                <input 
                    id="name" 
                    v-model="editedTask.name"
                    class="form-control"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </div>
            <div class="form-group">
                <label for="description">{{$t('description-optional')}}</label>
                <input 
                    id="description" 
                    v-model="editedTask.description"
                    class="form-control"
                    type="text" 
                    name="description" 
                    :placeholder="$t('description')"  />
            </div>
            <div class="form-group">
                <label for="type">{{$t('type')}}</label>
                <b-form-select
                    id="type"
                    v-model="editedTask.type"
                    name="type"
                    :options="taskTypes" />
                <base-form-error name="type" /> 
            </div>
            <div class="form-group">
                <label for="difficulty">{{'Difficulty: '+editedTask.difficulty+'/5'}}</label>
                <input 
                    id="difficulty"
                    v-model="editedTask.difficulty"
                    class="form-control"
                    type="range"
                    name="difficulty"
                    min="1"
                    max="5"
                    value="3" />
                <base-form-error name="difficulty" /> 
            </div>
            <div class="form-group">
                <label for="repeatable">{{$t('repeatable')}}</label>
                <b-form-select
                    id="repeatable"
                    v-model="editedTask.repeatable"
                    name="repeatable"
                    :options="repeatables" />
                <base-form-error name="repeatable" /> 
            </div>
            <div class="form-group">
                <p v-if="editedTask.taskList">{{ $t('task-list') }}: {{editedTask.taskList}}</p>
                <p v-if="editedTask.superTask">{{ $t('subtask-of') }}: {{editedTask.superTask}}</p>
            </div>
            <b-button type="submit" block>{{ $t('edit-task') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import {TASK_TYPES, REPEATABLES} from '../../constants/taskConstants';
import Vue from 'vue';

export default {
    components: {BaseFormError},
    props: {
        task: {
            /** @type {import('../../../types/task').Task} */
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            /** @type {import('../../../types/task').Task} */
            editedTask: {},
            taskTypes: TASK_TYPES,
            repeatables: REPEATABLES,
        }
    },
    mounted() {
        this.task ? this.editedTask = Vue.util.extend({}, this.task) : this.editedTask = {};
    },
    methods: {
        updateTask() {
            this.$store.dispatch('task/updateTask', this.editedTask).then(() => {
                this.close();
            });
        },
        close() {
            this.$emit('close');
        },
    },
}
</script>
