import {OverviewFieldGroups} from 'resources/types/global';

export const REPORT_REASONS = [
    {text: 'harassment', value: 'HARASSMENT'},
    {text: 'spam', value: 'SPAM'},
    {text: 'inappropriate-naming', value: 'NAME'},
    {text: 'other', value: 'OTHER'},
];

export const REPORTED_USER_FIELDS = [
    {
        label: 'username',
        key: 'username',
        sortable: true,
    },
    {
        label: 'report-amount',
        key: 'report_amount',
        sortable: true,
    },
    {
        label: 'last-report',
        key: 'last_report_date',
        sortable: true,
    },
    {
        label: 'actions',
        key: 'actions',
    },
    {
        label: 'banned',
        key: 'banned',
    },
];

export const REPORTED_USER_DETAILS_FIELDS = [
    {
        label: 'comment',
        key: 'comment',
    },
    {
        label: 'report-date',
        key: 'reported_date',
        sortable: true,
    },
    {
        label: 'reported-by',
        key: 'reported_by_name',
        sortable: true,
    },
    {
        label: 'conversation-id',
        key: 'conversation',
        sortable: true,
    },
    {
        label: 'reason',
        key: 'reason',
    },
    {
        label: 'actions',
        key: 'actions',
    },
];

export const BANNED_USERS_FIELDS = [
    {
        label: 'date',
        key: 'created_at',
        sortable: true,
    },
    {
        label: 'user',
        key: 'user',
        sortable: true,
    },
    {
        label: 'reason',
        key: 'reason',
    },
    {
        label: 'days',
        key: 'days',
        sortable: true,
    },
    {
        label: 'banned-by',
        key: 'admin',
        sortable: true,
    },
    {
        label: 'banned-until',
        key: 'banned_until',
        sortable: true,
    },
    {
        label: 'log',
        key: 'log',
    },
    {
        label: 'actions',
        key: 'actions',
    },
];

export const REPORTED_USER_OVERVIEW_FIELDS: OverviewFieldGroups = {
    fieldGroups: [
        {
            key: 'username',
            fields: [
                {
                    key: 'username',
                    label: 'username',
                    hidden: true,
                    sortable: true,
                },
            ],
            width: 2,
        },
        {
            label: 'reports',
            key: 'reports',
            fields: [
                {
                    label: 'report-amount',
                    key: 'report_amount',
                    sortable: true,
                },
                {
                    label: 'last-report',
                    key: 'last_report_date',
                    sortable: true,
                },
            ],
            width: 3,
        },
        {
            label: 'banned-until',
            key: 'banned_group',
            fields: [
                {
                    label: 'banned',
                    key: 'banned_until',
                    sortable: true,
                },
            ],
            width: 3,
        },
        {
            label: 'actions',
            key: 'actions_group',
            fields: [
                {
                    label: 'actions',
                    key: 'actions',
                },
            ],
            width: 2,
        },
    ],
    extend: [
        {
            label: '',
            key: 'extend',
        },
    ],
}