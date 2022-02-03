<template>
    <div v-if="this.bugs">
        <h3>{{ $t('admin-bug-panel-title') }}</h3>
        <div>            
            <p>
                sort by:
                <template v-for="sortable in sortables">
                    <button :key="sortable.value" v-on:click="sort(sortable.value)">{{sortable.value == 'type' ? `Type: ${types[types.findIndex(element => element.value == currentSortType)].text}` : sortable.text}}</button>
                </template>
                (click again to reverse order)
            </p>
        </div>
        <template v-for="bug in sortedBugs">
            <div :key="bug.id" :title="bug.title" class="bug">
                <div :class="headerColour(bug.severity) + ' d-flex header'">
                    <span>
                        {{bug.title}}
                        <button type="button" @click="editBugReport(bug)">editerino</button>
                    </span>
                    <span class="m-auto">{{bug.type}}</span>
                    <span class="ml-auto">{{bug.severity}}</span>
                </div>
                <div class="bug-content">
                    <p>Page: {{bug.page}}</p>
                    <p v-if="bug.image_link">Image: {{bug.image_link}}</p>
                    <p>Comment: "{{bug.comment}}"</p>
                    <p>Reported by user: {{bug.user_id}} </p>
                    <p>Status: {{bug.status}}</p>
                    <p>time: {{bug.time_created}}</p>
                    <span v-if="bug.admin_comment">Admin comment: "{{bug.admin_comment}}"</span>
                </div>
            </div>
        </template>
        <!---debug: currentSort: {{currentSort}} | currentSortDir: {{currentSortDir}} | currentSortType: {{currentSortType}}--->

        <b-modal id="edit-bug-report" hide-footer :title="$t('edit-bug-report')">
            <edit-bug-report :bug="bugReportToEdit" @close="closeEditBugReport"/>
        </b-modal>

    </div>
</template>


<script>
import {BUG_TYPES, BUG_SORTABLES, BUG_SEVERITY, BUG_DEFAULTS} from '../constants/bugConstants';
import {mapGetters} from 'vuex';
import EditBugReport from '../components/modals/EditBugReport.vue';
export default {
    components: {
        EditBugReport,
    },
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
        this.$store.dispatch('bugReport/fetchBugs');
    },
    computed: {
        ...mapGetters({
            bugs: 'bugReport/getBugs',
        }),
        //this may be needed for language support
        sortedTypes() {
            return this.types.sort();
        },
        sortedBugs() {
            return this.bugs.slice().sort((a,b) => {
                if(this.currentSort === 'type') {
                    let bugTypeLength = this.types.length;
                    let modifier = (bugTypeLength - this.types.findIndex(element => element.value == this.currentSortType));
                    let tempA = (this.types.findIndex(element => element.value == a.type) + modifier) % bugTypeLength;
                    let tempB = (this.types.findIndex(element => element.value == b.type) + modifier) % bugTypeLength;
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
        },

    },
    data() {
        return {
            currentSort: BUG_DEFAULTS.currentSort,
            currentSortDir: BUG_DEFAULTS.currentSortDir,
            currentSortType: BUG_DEFAULTS.currentSortType,
            sortables: BUG_SORTABLES,
            types: BUG_TYPES,
        }
    },    
    methods: {
        editBugReport() {
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
            if(sortInput === this.currentSort) {
                if(sortInput === 'type') {
                    this.currentSortType = this.types[((this.types.findIndex(element => element.value == this.currentSortType) +1) % this.types.length)].value;
                } else {
                    this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
                };
            } else {
            this.currentSort = sortInput;
            this.currentSortDir = BUG_DEFAULTS.currentSortDir;
            this.currentSortType = BUG_DEFAULTS.currentSortType;
            }
        },
    },
}
</script>

<style lang="scss" scoped>

//NOTE: Parsing the severity in the back-end will break the colours. Change the 'severity-#' to 'severity-low' etc
@import '../../assets/scss/variables';
.bug{
    border: 1px solid $grey;
    color: black;
    margin-bottom: 0.5rem;
    .header {
        padding: 0.2rem;
    }
    .bug-content {
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