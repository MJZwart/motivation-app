<template>
    <div>
        <div class="home-grid">
            <div class="task-lists">
                <div class="task-list">
                    <h3>Task list 1</h3>
                    <p>Dummy text</p>
                    <p>Dummy text</p>
                    <p>Dummy text</p>
                </div>
                <div class="task-list">
                    <h3>Task list 2</h3>
                    <p>Dummy text</p>
                    <p>Dummy text</p>
                    <p>Dummy text</p>
                </div>
                <div class="task-list">
                    <h3>New Task list </h3>
                </div>
            </div>

            <div class="character-tab">
                <div class="character">
                    <h3>Character</h3>
                    <p>Dummy text</p>
                    <p>Dummy text</p>
                    <p>Dummy text</p>
                </div>
            </div>

            <div class="friends-list">
                <div class="friends">
                    <h3>Friends</h3>
                    <p>Dummy text</p>
                    <p>Dummy text</p>
                    <p>Dummy text</p>
                </div>
            </div>
        </div>
        
    </div>
</template>


<script>
export default {
    mounted() {
        this.getTasks();
    },
    methods: {
        getTasks(){
            this.data = this.$store.dispatch('getTaskLists');
        },
    },
    data() {
        return {
            data: null,
        }
    },
    
}
</script>


<style>
    .home-grid {
        display: grid;
        column-gap: 25px;
    }
    .home-grid div div{
        border: 2px solid teal;
        border-radius: 20px;
        margin: 10px;
    }
    .task-lists{
        grid-row: 1/5;
        grid-column: 1;
        display: flex;
        flex-wrap: wrap;
    }
    .task-lists div{
        width: 45%;
    }
    .character-tab{
        grid-row: 1;
        grid-column: 2;
    }
    .friends-list{
        grid-row:2;
        grid-column: 2;
    }

    @media (max-width:730px){
        .task-lists{
            grid-row: 1;
            grid-column: 1;
            display: flex;
            flex-wrap: wrap;
        }
        .character-tab{
            grid-row: 2;
            grid-column: 1;
        }
        .friends-list{
            grid-row:3;
            grid-column: 1;
        }
        .task-lists div{
            width: 100%;
        }
    }
</style>