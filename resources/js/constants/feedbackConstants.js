export const FEEDBACK_TYPES = [
    {text: 'feedback', value: 'FEEDBACK'},
    {text: 'suggestion', value: 'SUGGESTION'},
    {text: 'question', value: 'QUESTION'},
    {text: 'other', value: 'OTHER'},
];

export const FEEDBACK_FIELDS = [
    {
        label: 'actions', 
        key: 'actions',
    },
    {
        label: 'type', 
        key: 'type',
        sortable: true,
    },
    {
        label: 'text', 
        key: 'text',
    },
    {
        label: 'user', 
        key: 'user',
    },
    {
        label: 'email', 
        key: 'email',
    },
    {
        label: 'created', 
        key: 'created_at',
        sortable: true,
    },
    {
        label: 'archived', 
        key: 'archived',
        sortable: true,
    },
];