<template>
    <div>
        <h3>{{ $t('admin-bug-report-panel-title') }}</h3>
        <div>            
            <p>
                sort by:
                <template v-for="sortable in sortables">
                    <button 
                        :key="sortable.value" 
                        v-on:click="sort(sortable.value)">
                        {{sortable.value == 'type' ? 
                            `Type: ${types[types.findIndex(element => element.value == currentSortType)].text}` : 
                            sortable.text}}
                    </button>
                </template>
                (click again to reverse order)
            </p>
        </div>
        <template v-for="bugReport in sortedBugReports">
            <div :key="bugReport.id" :title="bugReport.title" class="bugReport">
                <div :class="headerColour(bugReport.severity) + ' d-flex header'">
                    <span>
                        {{bugReport.title}}
                        <button type="button" @click="editBugReport(bugReport)">edit</button>
                    </span>
                    <span class="m-auto">{{parsedType(bugReport.type)}}</span>
                    <span class="ml-auto">{{bugReport.severity}}</span>
                </div>
                <div class="bug-report-content">
                    <p>Page: {{bugReport.page}}</p>
                    <p v-if="bugReport.image_link">Image: {{bugReport.image_link}}</p>
                    <p>Comment: "{{bugReport.comment}}"</p>
                    <p>Reported by user: {{bugReport.user_id}} </p>
                    <p>Status: {{bugReport.status}}</p>
                    <p>time: {{bugReport.time_created}}</p>
                    <span v-if="bugReport.admin_comment">Admin comment: "{{bugReport.admin_comment}}"</span>
                </div>
            </div>
        </template>
        <!---debug: currentSort: {{currentSort}} | currentSortDir: {{currentSortDir}} | currentSortType: {{currentSortType}}--->

        <b-modal id="edit-bug-report" hide-footer :title="$t('edit-bug-report')">
            <edit-bug-report :bugReport="bugReportToEdit" @close="closeEditBugReport"/>
        </b-modal>

    </div>
</template>


<script>
import {BUG_TYPES, BUG_SORTABLES, BUG_DEFAULTS} from '../constants/bugConstants';
import {mapGetters} from 'vuex';
import EditBugReport from '../components/modals/EditBugReport.vue';
export default {
    components: {
        EditBugReport,
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
        //     return this.types.sort();
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
            sortables: BUG_SORTABLES,
            types: BUG_TYPES,
            bugReportToEdit: null,
        }
    },    
    methods: {
        parsedType(type) {
            return BUG_TYPES.find(element => element.value == type).text;
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
                        this.types[((this.types.findIndex(
                            element => element.value == this.currentSortType) +1) % this.types.length)].value;
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
                        let bugTypesLength = this.types.length;
                        let modifier = (bugTypesLength - this.types.findIndex(element => element.value == this.currentSortType));
                        let tempA = (this.types.findIndex(element => element.value == a.type) + modifier) % bugTypesLength;
                        let tempB = (this.types.findIndex(element => element.value == b.type) + modifier) % bugTypesLength;
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