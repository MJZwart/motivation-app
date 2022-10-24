import {OverviewFieldGroups} from 'resources/types/global';

export const ALL_GROUP_FIELDS_OVERVIEW: OverviewFieldGroups = {
    fieldGroups:
    [
        {
            key: 'nameField',
            fields: [
                {
                    label: 'name', 
                    key: 'name',
                    sortable: true,
                    hidden: true,
                },
                {
                    label: 'description', 
                    key: 'description',
                },
            ],
            width: 9,
        },
        {
            fields: [
                {
                    label: 'details',
                    key: 'details',
                },
            ],
            width: 1,
        },
    ],
    extend: [
        {
            label: 'public',
            key: 'is_public',
        },
        {
            label: 'application-required',
            key: 'require_application',
        },
        {
            label: 'members',
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
                    label: 'name', 
                    key: 'name',
                    sortable: true,
                    hidden: true,
                },
                {
                    label: 'description', 
                    key: 'description',
                },
            ],
            width: 6,
        },
        {
            key: 'personal',
            fields: [
                {
                    label: 'member-since', 
                    key: 'joined',
                    sortable: true,
                },
                {
                    label: 'rank', 
                    key: 'rank',
                },
            ],
            width: 3,
        },
        {
            fields: [
                {
                    label: 'details',
                    key: 'details',
                },
            ],
            width: 1,
        },
    ],
    extend: [
        {
            label: 'public',
            key: 'is_public',
        },
        {
            label: 'application-required',
            key: 'require_application',
        },
        {
            label: 'members',
            key: 'members',
        },
    ],
}
