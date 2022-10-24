/**
 * @type {import('resources/types/global').Field[]}
 */
export const ACHIEVEMENT_FIELDS = [
    {label: 'image', key: 'image'},
    {label: 'name', key: 'name', sortable: true},
    {label: 'description', key: 'description', sortable: true},
    {label: 'trigger', key: 'trigger', sortable: true},
    {label: 'trigger-amount', key: 'trigger_amount', sortable: true},
    {label: 'trigger-type', key: 'trigger_type', sortable: true},
    {label: 'actions', key: 'actions'},
];

export const ACHIEVEMENT_DEFAULTS = {
    currentSort: 'trigger',
};

export const ACHIEVEMENT_TRIGGERS = [
    {
        type: 'TASKS_MADE',
        desc: 'ach-tasks-made',
    }, 
    {
        type: 'TASKS_COMPLETED',
        desc: 'ach-tasks-completed',
    }, 
    {
        type: 'REPEATABLE_COMPLETED',
        desc: 'ach-repeatable-completed',
    }, 
    {
        type: 'FRIENDS',
        desc: 'ach-friends',
    },
];