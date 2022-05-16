export const REPORT_REASONS = [
    {text: 'Harassment', value: 'HARASSMENT'},
    {text: 'Spam', value: 'SPAM'},
    {text: 'Inappropriate naming', value: 'NAME'},
    {text: 'Other', value: 'OTHER'},
];

export const REPORTED_USER_FIELDS = [
    {
        label: 'Username',
        key: 'username',
        sortable: true,
    },
    {
        label: '# of Reports',
        key: 'report_amount',
        sortable: true,
    },
    {
        label: 'Last Report',
        key: 'last_report_date',
        sortable: true,
    },
    {
        label: 'Actions',
        key: 'actions',
    },
    {
        label: 'Banned',
        key: 'banned',
    },
];

export const REPORTED_USER_DETAILS_FIELDS = [
    {
        label: 'Comment',
        key: 'comment',
    },
    {
        label: 'Report Date',
        key: 'reported_date',
        sortable: true,
    },
    {
        label: 'Reported by',
        key: 'reported_by_name',
        sortable: true,
    },
    {
        label: 'Conversation ID',
        key: 'conversation',
        sortable: true,
    },
    {
        label: 'Reason',
        key: 'reason',
    },
    {
        label: 'Actions',
        key: 'actions',
    },
];

export const BANNED_USERS_FIELDS = [
    {
        label: 'Date',
        key: 'created_at',
        sortable: true,
    },
    {
        label: 'User',
        key: 'user',
    },
    {
        label: 'Reason',
        key: 'reason',
    },
    {
        label: 'Days',
        key: 'days',
        sortable: true,
    },
    {
        label: 'Banned by',
        key: 'admin',
    },
    {
        label: 'Banned until',
        key: 'banned_until',
    },
    {
        label: 'Actions',
        key: 'actions',
    },
];