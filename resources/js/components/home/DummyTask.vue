<template>
    <div>
        <p class="task-title d-flex">
            <Tooltip :text="$t('complete-task')">
                <FaIcon 
                    icon="fa-regular fa-square-check"
                    class="icon small green" />
            </Tooltip>
            {{task.name}}             
            <span class="ml-auto">
                <Tooltip :text="$t('new-sub-task')">
                    <FaIcon 
                        icon="fa-solid fa-square-plus"
                        class="icon small green" />
                </Tooltip>
                <Tooltip :text="$t('edit-task')">
                    <FaIcon 
                        icon="fa-regular fa-pen-to-square"
                        class="icon small" />
                </Tooltip>
                <Tooltip :text="$t('delete-task')">
                    <FaIcon 
                        icon="fa-solid fa-trash"
                        class="icon small red" />
                </Tooltip>
            </span>
            
        </p>
        
        <p class="task-description">{{task.description}}</p>

        <div v-for="subTask in task.tasks" :key="subTask.id" class="sub-task">
            <p class="task-title d-flex">
                <FaIcon icon="fa-solid fa-arrow-turn-up" rotation="90" />
                <Tooltip :text="$t('complete-sub-task')">
                    <FaIcon 
                        icon="fa-regular fa-square-check"
                        class="icon small green" />
                </Tooltip>
                {{subTask.name}}
                <Tooltip :text="$t('edit-sub-task')">
                    <FaIcon 
                        icon="fa-regular fa-pen-to-square"
                        class="icon small" />
                </Tooltip>
                <Tooltip :text="$t('delete-sub-task')">
                    <FaIcon 
                        icon="fa-solid fa-trash"
                        class="icon small red" />
                </Tooltip>
            </p>
            <p class="task-description">{{subTask.description}}</p>
        </div>
    </div>
</template>


<script>
import Tooltip from '../bootstrap/Tooltip.vue';
export default {
    components: {Tooltip},
    props: {
        task: {
            /** @type {import('resources/types/task').Task} */
            type: Object,
            required: true,
        },
    },
}
</script>