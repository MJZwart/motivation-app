<template>
    <div>
        <h3>{{ $t('actions') }}</h3>
        Filter by:
        <div>
            <div class="form-group">
                {{$t('type')}}
                <Multiselect
                    v-model="activeFilters.type"
                    :options="actionTypes"
                    mode="tags"
                    valueProp="action_type"
                    label="action_type"
                />
            </div>
            <div class="form-group">
                {{$t('users')}}
                <Multiselect
                    v-model="activeFilters.users"
                    :options="users"
                    searchable
                    label="username"
                    valueProp="id"
                    mode="tags" />

            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';
import {StrippedUser} from 'resources/types/user';
import Multiselect from '@vueform/multiselect';

export type ActionType = {
    action_type: string;
}
export type ActionFilters= {
    type: string[] | null,
    users: StrippedUser[] | null,
}

const adminStore = useAdminStore();

const actionTypes = ref<ActionType[]>([]);
const users = ref<StrippedUser[]>([]);
// const selectedUsers = ref<StrippedUser[]>([]);
const activeFilters = ref<ActionFilters>({
    type: null,
    users: null,
});

onMounted(async() => {
    const data = await adminStore.getActionFilters();
    actionTypes.value = data.types;
    users.value = data.users;
});

// function selectUser(user: StrippedUser) {
//     selectedUsers.value.push(user);
//     users.value.splice(users.value.findIndex(item => item.id === user.id), 1);
// }
// function unselectUser(user: StrippedUser) {
//     users.value.push(user);
//     selectedUsers.value.splice(selectedUsers.value.findIndex(item => item.id === user.id), 1);
// }
</script>
<!-- <style src="@vueform/multiselect/themes/default.css"></style> -->
<style lang="scss">
.multiselect {
    align-items: center;
    background: var(--input-background);
    border: var(--ms-border-width, 1px) solid var(--border-color);
    border-radius: var(--ms-radius, 4px);
    box-sizing: border-box;
    cursor: pointer;
    display: flex;
    font-size: var(--ms-font-size, 1rem);
    justify-content: flex-end;
    margin: 0 auto;
    min-height: calc(var(--ms-border-width, 1px)*2 + var(--ms-font-size, 1rem)*var(--ms-line-height, 1.375) + var(--ms-py, .5rem)*2);
    outline: none;
    position: relative;
    width: 100%;

    .multiselect-caret {
        background-color: var(--ms-caret-color, #999);
        flex-grow: 0;
        flex-shrink: 0;
        margin: 0 var(--ms-px, .875rem) 0 0;
        -webkit-mask-image: url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z'/%3E%3C/svg%3E");
        mask-image: url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z'/%3E%3C/svg%3E");
        pointer-events: none;
        position: relative;
        transform: rotate(0deg);
        transition: transform .3s;
        z-index: 10;
    }

    .multiselect-tags {
        align-items: center;
        display: flex;
        flex-grow: 1;
        flex-shrink: 1;
        flex-wrap: wrap;
        margin: var(--ms-tag-my, .25rem) 0 0;
        padding-left: var(--ms-py, .5rem);

        .multiselect-tag {
            align-items: center;
            background: var(--ms-tag-bg, #10b981);
            border-radius: var(--ms-tag-radius, 4px);
            color: var(--ms-tag-color, #fff);
            display: flex;
            font-size: var(--ms-tag-font-size, .875rem);
            font-weight: var(--ms-tag-font-weight, 600);
            line-height: var(--ms-tag-line-height, 1.25rem);
            margin-bottom: var(--ms-tag-my, .25rem);
            margin-right: var(--ms-tag-mx, .25rem);
            padding: var(--ms-tag-py, .125rem) 0 var(--ms-tag-py, .125rem) var(--ms-tag-px, .5rem);
            white-space: nowrap;

            .multiselect-tag-remove {
                align-items: center;
                border-radius: var(--ms-tag-remove-radius, 4px);
                display: flex;
                justify-content: center;
                margin: var(--ms-tag-remove-my, 0) var(--ms-tag-remove-mx, .125rem);
                padding: var(--ms-tag-remove-py, .25rem) var(--ms-tag-remove-px, .25rem);

                .multiselect-tag-remove-icon {
                    background-color: currentColor;
                    display: inline-block;
                    height: .75rem;
                    -webkit-mask-image: url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m207.6 256 107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z'/%3E%3C/svg%3E");
                    mask-image: url("data:image/svg+xml;charset=utf-8,%3Csvg viewBox='0 0 320 512' fill='currentColor' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='m207.6 256 107.72-107.72c6.23-6.23 6.23-16.34 0-22.58l-25.03-25.03c-6.23-6.23-16.34-6.23-22.58 0L160 208.4 52.28 100.68c-6.23-6.23-16.34-6.23-22.58 0L4.68 125.7c-6.23 6.23-6.23 16.34 0 22.58L112.4 256 4.68 363.72c-6.23 6.23-6.23 16.34 0 22.58l25.03 25.03c6.23 6.23 16.34 6.23 22.58 0L160 303.6l107.72 107.72c6.23 6.23 16.34 6.23 22.58 0l25.03-25.03c6.23-6.23 6.23-16.34 0-22.58L207.6 256z'/%3E%3C/svg%3E");
                    -webkit-mask-position: center;
                    mask-position: center;
                    -webkit-mask-repeat: no-repeat;
                    mask-repeat: no-repeat;
                    -webkit-mask-size: contain;
                    mask-size: contain;
                    opacity: .8;
                    width: .75rem;
                }
            }
        }

        .multiselect-tags-search-wrapper {
            display: inline-block;
            flex-grow: 1;
            flex-shrink: 1;
            height: 100%;
            margin: 0 var(--ms-tag-mx, 4px) var(--ms-tag-my, 4px);
            position: relative;

            .multiselect-tags-search-copy {
                display: inline-block;
                height: 1px;
                visibility: hidden;
                white-space: pre-wrap;
                width: 100%;
            }
        }
    }

    .multiselect-dropdown.is-hidden {
        display: none;
    }

    .multiselect-dropdown {
        -webkit-overflow-scrolling: touch;
        background: var(--input-background);
        border: var(--ms-dropdown-border-width, 1px) solid var(--border-color);
        border-radius: 0 0 var(--ms-dropdown-radius, 4px) var(--ms-dropdown-radius, 4px);
        bottom: 0;
        display: flex;
        flex-direction: column;
        left: calc(var(--ms-border-width, 1px)*-1);
        margin-top: calc(var(--ms-border-width, 1px)*-1);
        max-height: 15rem;
        max-height: var(--ms-max-height, 10rem);
        outline: none;
        overflow-y: scroll;
        position: absolute;
        right: calc(var(--ms-border-width, 1px)*-1);
        transform: translateY(100%);
        z-index: 100;

        .multiselect-options {
            display: flex;
            flex-direction: column;
            list-style: none;
            margin: 0;
            padding: 0;

            .multiselect-option {
                align-items: center;
                box-sizing: border-box;
                cursor: pointer;
                display: flex;
                font-size: var(--ms-option-font-size, 1rem);
                justify-content: flex-start;
                line-height: var(--ms-option-line-height, 1.375);
                padding: var(--ms-option-py, .5rem) var(--ms-option-px, .75rem);
                text-align: left;
                text-decoration: none;
            }
        }
    }
}
</style>