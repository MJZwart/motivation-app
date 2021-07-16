<template>
    <div>
        <div :class="{unread: !notification.read}">
            <span class="frame-title">{{notification.title}}</span>
            <div class="side-border bottom-border grid-2 small-text">
                <p>{{notification.text}}</p>
                <p>Received on: {{notification.created_at}}</p>
            </div>
        </div>
        <br />
    </div>
</template>


<script>
export default {
    props: {
        notification: Object,
    },
    
}
</script>


<style>
.unread {
    background-color:#eeeeee;
    border-radius:5px;
}
</style>