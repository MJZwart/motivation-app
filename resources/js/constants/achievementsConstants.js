/**
 * @type {import('resources/types/global').Field[]}
 */
export const ACHIEVEMENT_FIELDS = [
    {label: 'Image', key: 'image'},
    {label: 'Name', key: 'name', sortable: true},
    {label: 'Description', key: 'description', sortable: true},
    {label: 'Trigger', key: 'trigger', sortable: true},
    {label: 'Trigger amount', key: 'trigger_amount', sortable: true},
    {label: 'Trigger type', key: 'trigger_type', sortable: true},
    {label: 'Actions', key: 'actions'},
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