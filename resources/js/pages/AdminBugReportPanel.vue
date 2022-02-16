<template>
    <div>
        <h3>{{ $t('admin-bug-report-panel-title') }}</h3>
        <div>            
            <p>{{ $t('sort-by') }}:</p>
            <b-button-group>
                <template v-for="sortable in bugSortables">
                    <b-button :key="sortable.value"
                              variant="primary"
                              v-on:click="sort(sortable.value)">
                        {{sortable.value == 'type' ? 
                            `Type: ${parsedType(currentSortType)}` : 
                            sortable.text}}
                    </b-button>
                </template>
            </b-button-group>
            ({{ $t('reverse-order') }})
            
        </div>
        <template v-for="bugReport in sortedBugReports">
            <div :key="bugReport.id" :title="bugReport.title" class="bugReport">
                <div :class="headerColour(bugReport.severity) + ' d-flex header'">
                    <span>
                        {{bugReport.title}}
                    </span>
                    <span class="m-auto">{{parsedType(bugReport.type)}}</span>
                    <span class="ml-auto">{{parsedSeverity(bugReport.severity)}}</span>
                </div>
                <div class="bug-report-content">
                    <p>{{ $t('page') }}: {{bugReport.page}}</p>
                    <p v-if="bugReport.image_link">{{ $t('image') }}: {{bugReport.image_link}}</p>
                    <p>{{ $t('comment') }}: "{{bugReport.comment}}"</p>
                    <p>{{ $t('reported-by') }}: {{bugReport.user_id}} </p>
                    <p>{{ $t('status') }}: {{parsedStatus(bugReport.status)}}</p>
                    <p>{{ $t('time') }}: {{bugReport.time_created}}</p>
                    <span v-if="bugReport.admin_comment">{{ $t('admin-comment') }}: "{{bugReport.admin_comment}}"</span>
                    <b-button-group>
                        <b-button type="button" @click="editBugReport(bugReport)">{{ $t('edit-bug-report') }}</b-button>
                        <b-button type="button" @click="sendMessageToBugReportAuthor(bugReport.user_id)">
                            {{ $t('send-message') }}
                        </b-button>
                    </b-button-group>
                </div>
            </div>
        </template>
        <!---debug: currentSort: {{currentSort}} | currentSortDir: {{currentSortDir}} | currentSortType: {{currentSortType}}--->

        <b-modal id="edit-bug-report" hide-footer :title="$t('edit-bug-report')">
            <edit-bug-report :bugReport="bugReportToEdit" @close="closeEditBugReport"/>
        </b-modal>
        <b-modal id="send-message-to-bug-report-author" hide-footer :title="$t('send-message-to-bug-report-author')">
            <send-message :user="bugReportAuthor" @close="closeSendMessageToBugReportAuthor"/>
        </b-modal>

    </div>
</template>


<script>
import {BUG_TYPES, BUG_SORTABLES, BUG_DEFAULTS, BUG_SEVERITY, BUG_STATUS} from '../constants/bugConstants';
import {mapGetters} from 'vuex';
import EditBugReport from '../components/modals/EditBugReport.vue';
import SendMessage from '../components/modals/SendMessage.vue';
export default {
    components: {
        EditBugReport,
        SendMessage,
    },
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
        this.$store.dispatch('bugReport/fetchBugReports');
    },
    computed: {
        ...mapGetters({
            bugReports: 'bugReport/getBugReports',
        }),
        //this may be needed for language support
        // sortedTypes() {
        //     return this.bugTypes.sort();
        // },
        sortedBugReports() {
            return this.sortBugReports();
        },
    },
    data() {
        return {
            currentSort: BUG_DEFAULTS.currentSort,
            currentSortDir: BUG_DEFAULTS.currentSortDir,
            currentSortType: BUG_DEFAULTS.currentSortType,
            bugSortables: BUG_SORTABLES,
            bugTypes: BUG_TYPES,
            bugSeverity: BUG_SEVERITY,
            bugStatus: BUG_STATUS,
            bugReportToEdit: null,
            bugReportAuthor: null,
        }
    },    
    methods: {
        parsedKey(key, array) {
            return array.find(element => element.value == key).text
        },
        parsedType(type) {
            return this.parsedKey(type, this.bugTypes);
        },
        parsedSeverity(severity) {
            return this.parsedKey(severity, this.bugSeverity);
        },
        parsedStatus(status) {
            return this.parsedKey(status, this.bugStatus)
        },
        sendMessageToBugReportAuthor(authorId) {
            this.$store.dispatch('clearErrors');
            this.bugReportAuthor = {id: authorId};
            this.$bvModal.show('send-message-to-bug-report-author');
        },
        closeSendMessageToBugReportAuthor() {
            this.$bvModal.hide('send-message-to-bug-report-author');
        },
        editBugReport(bugReport) {
            this.$store.dispatch('clearErrors');
            this.bugReportToEdit = bugReport;
            this.$bvModal.show('edit-bug-report');

        },
        closeEditBugReport() {
            this.$bvModal.hide('edit-bug-report');
        },
        headerColour(severity) {
            return 'severity-' + severity;
        },
        sort(sortInput) {
            //if sortInput == current sort, reverse
            if (sortInput === this.currentSort) {
                if (sortInput === 'type') {
                    this.currentSortType = 
                        this.bugTypes[((this.bugTypes.findIndex(
                            element => element.value == this.currentSortType) +1) % this.bugTypes.length)].value;
                } else {
                    this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
                }
            } else {
                this.currentSort = sortInput;
                this.currentSortDir = BUG_DEFAULTS.currentSortDir;
                this.currentSortType = BUG_DEFAULTS.currentSortType;
            }
        },
        sortBugReports() {
            if (this.bugReports) {
                // eslint-disable-next-line complexity
                return this.bugReports.slice().sort((a,b) => {
                    if(this.currentSort === 'type') {
                        let bugTypesLength = this.bugTypes.length;
                        let modifier = (bugTypesLength - this.bugTypes.findIndex(element => element.value == this.currentSortType));
                        let tempA = (this.bugTypes.findIndex(element => element.value == a.type) + modifier) % bugTypesLength;
                        let tempB = (this.bugTypes.findIndex(element => element.value == b.type) + modifier) % bugTypesLength;
                        if(tempA < tempB) return -1;
                        if(tempA > tempB) return 1;
                    } else {
                        let modifier = 1;
                        if(this.currentSortDir === 'desc') modifier = -1;
                        if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                        if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                    }
                    return 0;
                })
            }
        },
    },
}
</script>

<style lang="scss" scoped>

@import '../../assets/scss/variables';
.bugReport{
    border: 1px solid $grey;
    color: black;
    margin-bottom: 0.5rem;
    .header {
        padding: 0.2rem;
    }
    .bugReport-content {
        p {
            margin-bottom: 0.2rem;
        }
    }
    .severity-1 {
        background-color: $green;
    }
    .severity-2 {
        background-color: $yellow;
    }
    .severity-3 {
        background-color: $orange;
    }
    .severity-4 {
        color: white;
        background-color: $red;
    }
    .severity-5 {
        color: white;
        background-color: $darkred;
    }  
}

</style>