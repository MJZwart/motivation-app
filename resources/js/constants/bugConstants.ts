import {OverviewFieldGroups} from 'resources/types/global';

export const BUG_SEVERITY = [
    {text: 'bug-low', value: 1}, 
    {text: 'bug-medium', value: 2},
    {text: 'bug-high', value: 3}, 
    {text: 'bug-severe', value: 4}, 
    {text: 'bug-critical', value: 5}];

export const BUG_TYPES = [
    {text: 'design', value: 'DESIGN'}, 
    {text: 'language', value: 'LANGUAGE'},
    {text: 'functionality', value: 'FUNCTIONALITY'}, 
    {text: 'other', value: 'OTHER'}];

export const BUG_REPORT_OVERVIEW_FIELDS: OverviewFieldGroups = {
    fieldGroups: [
        {
            key: 'title',
            fields: [
                {
                    key: 'title',
                    label: 'title',
                    hidden: true,
                    sortable: true,
                },
            ],
            width: 2,
        },
        {
            key: 'facts',
            fields: [
                {
                    key: 'page',
                    label: 'page',
                },
                {
                    key: 'type',
                    label: 'type',
                    sortable: true,
                },
                {
                    key: 'severity',
                    label: 'severity',
                    sortable: true,
                },
            ],
            width: 2,
        },
        {
            key: 'details',
            fields: [
                {
                    label: 'diagnostics',
                    key: 'diagnostics',
                },
                {
                    label: 'comment',
                    key: 'comment',
                },
                {
                    label: 'admin-comment',
                    key: 'admin_comment',
                },
            ],
            width: 3,
        },
        {
            key: 'report',
            fields: [
                {
                    key: 'time_created',
                    label: 'time-created',
                    sortable: true,
                },
                {
                    key: 'username',
                    label: 'user',
                    sortable: true,
                },
                {
                    key: 'image',
                    label: 'image',
                },
            ],
            width: 2,
        },
        {
            key: 'action',
            fields: [
                {
                    key: 'status',
                    label: 'status',
                    sortable: true,
                },
                {
                    key: 'actions',
                    label: 'actions',
                },
            ],
            width: 1,
        },
    ],
}

export const BUG_STATUS = [
    {text: 'reported', value: 0},
    {text: 'in-progress', value: 1},
    {text: 'done', value: 2},
    {text: 'resolved', value: 3}];
