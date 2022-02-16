export const BUG_SEVERITY = [
    {text: 'Low (Not too important)', value: 1}, 
    {text: 'Medium (A bit odd/annoying)', value: 2},
    {text: 'High (Quite limiting)', value: 3}, 
    {text: 'Severe (Very limiting)', value: 4}, 
    {text: 'Critical (Site-breaking)', value: 5}];

export const BUG_TYPES = [
    {text: 'Design', value: 'DESIGN'}, 
    {text: 'Language', value: 'LANGUAGE'},
    {text: 'Functionality', value: 'FUNCTIONALITY'}, 
    {text: 'Other', value: 'OTHER'}];

export const BUG_SORTABLES = [
    {text: 'Time Created', key: 'time_created', sortable: true},
    {text: 'Title', key: 'title', sortable: true},
    {text: 'Page', key: 'page', sortable: true},
    {text: 'Type', key: 'type', sortable: true},
    {text: 'Severity', key: 'severity', sortable: true},
    {text: 'User ID', key: 'user_id'},
    {text: 'Status', key: 'status', sortable: true},
    {text: 'Image', key: 'image'},
    {text: 'Comment', key: 'comment'},
    {text: 'Admin comment', key: 'admin_comment'},
];

export const BUG_STATUS = [
    {text: 'Reported', value: 0},
    {text: 'In progress', value: 1},
    {text: 'Done', value: 2},
    {text: 'Resolved', value: 3}];

export const BUG_DEFAULTS = {
    currentSort: 'time_created',
    currentSortDir: 'desc',
    currentSortType: 'DESIGN'};