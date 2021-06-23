<template>
    <div>
        <ul :class="'response ' + status">
            <li v-for="value in responseMessage" :key="value[0]">{{value[0]}}</li>
        </ul>
    </div>
</template>


<script>
import { mapGetters } from "vuex";
export default {
    computed: {
        ...mapGetters({
            responseMessage: "getResponseMessage",
            status: "getStatus",
        }),
    },
}
</script>


<style>
.response{
    display: block;
    padding:15px;
    margin:5px;
    border-radius: 0.5rem;
    list-style:none;
}

.hidden{
    display: hidden;
    padding: 0;
    margin: 5;
}
.response.error{
    color: #721c24;
    background-color:#f8d7da;
    border: 2px solid #f5c6cb;
}
.response.success{
    color: #155724;
    background-color: #d4edda;
    border: 2px solid #c3e6cb;
} 
/*
.response.warning{
    color: #856404;
    background-color: #fff3cd;
    border: 2px solid #ffeeba;
}
.response.info{
    color: #0c5460;
    background-color: #d1ecf1;
    border: 2px solid #bee5eb;
} */
</style>