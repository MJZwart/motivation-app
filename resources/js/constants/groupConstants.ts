import {OverviewFieldGroups} from 'resources/types/global';

export const ALL_GROUP_FIELDS_OVERVIEW: OverviewFieldGroups = {
    fieldGroups:
    [
        {
            key: 'nameField',
            fields: [
                {
                    label: 'Name', 
                    key: 'name',
                    sortable: true,
                    hidden: true,
                },
                {
                    label: 'Description', 
                    key: 'description',
                },
            ],
            width: 9,
        },
        {
            fields: [
                {
                    label: 'Details',
                    key: 'details',
                },
            ],
            width: 1,
        },
    ],
    extend: [
        {
            label: 'Public',
            key: 'is_public',
        },
        {
            label: 'Application required',
            key: 'require_application',
        },
        {
            label: 'Members',
            key: 'members',
        },
    ],
}

export const MY_GROUP_FIELDS_OVERVIEW: OverviewFieldGroups = {
    fieldGroups: [
        {
            key: 'nameField',
            fields: [
                {
                    label: 'Name', 
                    key: 'name',
                    sortable: true,
                    hidden: true,
                },
                {
                    label: 'Description', 
                    key: 'description',
                },
            ],
            width: 6,
        },
        {
            key: 'personal',
            fields: [
                {
                    label: 'Member since', 
                    key: 'joined',
                    sortable: true,
                },
                {
                    label: 'Rank', 
                    key: 'rank',
                },
            ],
            width: 3,
        },
        {
            fields: [
                {
                    label: 'Details',
                    key: 'details',
                },
            ],
            width: 1,
        },
    ],
    extend: [
        {
            label: 'Public',
            key: 'is_public',
        },
        {
            label: 'Application required',
            key: 'require_application',
        },
        {
            label: 'Members',
            key: 'members',
        },
    ],
}
