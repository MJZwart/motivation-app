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

export const BUG_REPORT_OVERVIEW_FIELDS = [
    {label: 'time-created', key: 'time_created', sortable: true},
    {label: 'title', key: 'title', sortable: true},
    {label: 'page', key: 'page', sortable: true},
    {label: 'type', key: 'type', sortable: true},
    {label: 'severity', key: 'severity', sortable: true},
    {label: 'user', key: 'user'},
    {label: 'status', key: 'status', sortable: true},
    {label: 'image', key: 'image'},
    {label: 'comment', key: 'comment'},
    {label: 'admin-comment', key: 'admin_comment'},
    {label: 'diagnostics', key: 'diagnostics'},
    {label: 'actions', key: 'actions'},
];

export const BUG_STATUS = [
    {text: 'reported', value: 0},
    {text: 'in-progress', value: 1},
    {text: 'done', value: 2},
    {text: 'resolved', value: 3}];

export const BUG_DEFAULTS = {
    currentSort: 'time_created',
    currentSortDir: 'desc',
    currentSortType: 'DESIGN'};