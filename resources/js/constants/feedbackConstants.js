export const FEEDBACK_TYPES = [
    {text: 'Feedback', value: 'FEEDBACK'},
    {text: 'Suggestion', value: 'SUGGESTION'},
    {text: 'Question', value: 'QUESTION'},
    {text: 'Other', value: 'OTHER'},
];

export const FEEDBACK_FIELDS = [
    {
        label: 'Actions', 
        key: 'actions',
    },
    {
        label: 'Type', 
        key: 'type',
        sortable: true,
    },
    {
        label: 'Text', 
        key: 'text',
    },
    {
        label: 'User', 
        key: 'user',
    },
    {
        label: 'Email', 
        key: 'email',
    },
    {
        label: 'Created', 
        key: 'created_at',
        sortable: true,
    },
    {
        label: 'Archived', 
        key: 'archived',
        sortable: true,
    },
];