<template>
    <div>
        <b>Manage your task list</b>
        <p>
            <FaIcon :icon="['far', 'pen-to-square']" class="small primary" />
            Edit your task or task list. You can change the name of your task list or the details of your task at any time.
        </p>
        <p>
            <FaIcon icon="trash" class="small red" />
            Delete task or task list. If you have an active character or village, you will not gain any points towards their levels.
        </p>
        <p>
            <FaIcon icon="square-plus" class="small green" />
            Add new sub task. Use these to divide your bigger tasks up in smaller, bite-sized chunks. You can then finish each sub task separately or finish off the entire task in one go. Note that you will gain experience for these sub tasks as well, if you haven't already marked them as completed before.
        </p>
        <p>
            <FaIcon :icon="['far', 'square-check']" class="small green" />
            Complete your task. If you have an active character or village linked, you will gain experience points towards their levels.
        </p>
    </div>
</template>