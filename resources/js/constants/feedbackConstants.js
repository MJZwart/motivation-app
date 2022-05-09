export const FEEDBACK_TYPES = [
    {text: 'Feedback', value: 'FEEDBACK'},
    {text: 'Suggestion', value: 'SUGGESTION'},
    {text: 'Question', value: 'QUESTION'},
    {text: 'Other', value: 'OTHER'},
];

export const FEEDBACK_FIELDS = [
    {
        label: 'Created', 
        key: 'created_at',
        sortable: true,
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
        label: 'Email', 
        key: 'email',
    },
    {
        label: 'User', 
        key: 'user',
    },
];