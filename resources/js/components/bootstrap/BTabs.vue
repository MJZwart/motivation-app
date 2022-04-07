<template>
    <div class="tabs" :class="{'row': vertical}">
        <ul class="tabs-header" :class="{'col-auto': vertical, 'card': card, 'row': !vertical}">
            <li 
                v-for="(tab, index) in tabs" 
                :key="tab.title" 
                class="tab"
                :class='getTabClass(index)'
                @click="selectTab(index)"
            >
                {{ tab.title }}
            </li>
        </ul>
        <div :class="{'col': vertical}" class="tab-content">
            <slot  />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        options: {
            type: Array,
            required: false,
        },
        vertical: {
            type: Boolean,
            required: false,
            default: false,
        },
        card: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    data() {
        return {
            selectedIndex: 0,
            tabs: [],
        }
    },
    created() {
        this.tabs = this.$children;
    },
    mounted() {
        this.selectTab(0);
    },
    methods: {
        selectTab(i) {
            this.selectedIndex = i;
            this.tabs.forEach((tab, index) => {
                tab.isActive = (index === i)
            });
        },
        getTabClass(index) {
            let className = index == this.selectedIndex ? 'active-tab' : '';
            if (this.options) {
                this.options.forEach(element => {
                    className += ' ' + element;
                });
            }
            return className;
        },
    },
}
</script>

<style lang="scss" scoped>
@import '../../../assets/scss/variables';
.card {
    padding: 0.75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0, 0, 0, 0.03);
}
.tabs{
    margin-right: 0;
    margin-left: 0;
    list-style: none;

    .tabs-header{
        border-bottom: 1px solid $background-darker;

        .tab{
            margin-bottom: -1px;
            border: 1px solid transparent;
            border-top-left-radius: 0.25rem;
            border-top-right-radius: 0.25rem;
            display: block;
            padding: 0.5rem 1rem;
            cursor:pointer;
        }
        .active-tab{
            color: #495057;
            background-color: $white;
            border-color: $background-darker $background-darker $white;
        }
        .active-tab.pills{
            color: $primary-text !important;
            background-color: $primary !important;
            border-radius: 0.25rem !important;
        }
    }
}
</style>