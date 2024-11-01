export const EXPERIENCE_POINTS_FIELDS = [
    {
        key: 'level', 
        label: 'level', 
        type: 'number',
        editable: false,
        sortable: true,
    },
    {
        key: 'experience_points', 
        label: 'points',
        type: 'number',
        editable: true, 
        class: 'points-col',
        sortable: true,
    },
];

export const VILLAGE_EXP_GAIN_FIELDS = [
    {
        key: 'task_type',
        label: 'task-type',
        editable: false,
    },
    {
        key: 'economy',
        label: 'economy',
        editable: true,
    },
    {
        key: 'labour',
        label: 'labour',
        editable: true,
    },
    {
        key: 'craft',
        label: 'craft',
        editable: true,
    },
    {
        key: 'art',
        label: 'art',
        editable: true,
    },
    {
        key: 'community',
        label: 'community',
        editable: true,
    },
    {
        key: 'level',
        label: 'level',
        editable: true,
    },
    {
        key: 'coins',
        label: 'coins',
        editable: true,
    },
];