<template>
    <div v-if="this.bugs">
        <h3>{{ $t('admin-bug-panel-title') }}</h3>
        <div>
            <p>
                sort by:
                <template v-for="sortable in sortables">
                    <button :key="sortable" v-on:click="sort(sortable)">{{sortable == 'type' ? `${sortable}: ${currentSortType}` : sortable}}</button>
                </template>
                (click again to reverse order)
            </p>
        </div>
        <template v-for="bug in sortedBugs">
            <div :key="bug.id" :title="bug.title" class="bug">
                <div :class="headerColour(bug.severity) + ' d-flex header'">
                    <span>{{bug.title}}</span>
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
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
export default {
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
        this.$store.dispatch('bugReport/fetchBugs');
    },
    computed: {
        ...mapGetters({
            bugs: 'bugReport/getBugs',
        }),        
        sortedBugs() {
            return this.bugs.sort((a,b) => {
                let modifier = 1;
                if(this.currentSort === 'type') {
                    modifier = (this.types.length - this.types.indexOf(this.currentSortType));
                    let tempA = (this.types.indexOf(a[this.currentSort]) + modifier) % this.types.length;
                    let tempB = (this.types.indexOf(b[this.currentSort]) + modifier) % this.types.length;
                    if(tempA < tempB) return -1;
                    if(tempA > tempB) return 1;
                } else {
                    if(this.currentSortDir === 'desc') modifier = -1;
                    if(a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
                    if(a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
                }
                return 0;
            })
        },
        //this may be needed for language support
        sortedTypes() {
            return this.types.sort();
        }

    },
    data() {
        return {
            currentSort: 'time_created',
            currentSortDir: 'asc',
            currentSortType: 'Design',
            sortables: ['time_created', 'title', 'page', 'type', 'severity', 'user_id', 'status',],
            types: ['Design', 'Functionality', 'Language', 'Other',],
        }
    },    
    methods: {
        headerColour(severity) {
            return 'severity-' + severity;
        },
        sort(sortInput) {
            //if sortInput == current sort, reverse
            if(sortInput === this.currentSort) {
                if(sortInput === 'type') {
                    this.currentSortType = this.types[((this.types.indexOf(this.currentSortType) +1) % this.types.length)]
                } else {
                    this.currentSortDir = this.currentSortDir==='asc'?'desc':'asc';
                };
            } else {
            this.currentSort = sortInput;
            this.currentSortType = 'Design';
            this.currentSortDir = 'asc';
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