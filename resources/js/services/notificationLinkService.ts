import axios from 'axios';
import {useFriendStore} from '/js/store/friendStore';

/**
 * Handles all notification links. Checks what the url type is (eg 'friend')
 * and handles accordingly. All other types are handled as direct back-end link
 */
export async function handleNotificationLink(apiType: string, url: string): Promise<boolean> {
    const urlType = url.split('/')[1];
    let success = false;
    switch (urlType) {
        case 'friend':
            success = await handleFriendRequests(url);
            break;
        case 'groups':
            success = await handleGroupInviteRequests(url);
            break;
        default:
            success = await handleOtherLinks(apiType, url);
            break;
    }
    return success;
}

/**
 * Handles the friend requests. It takes the type of action (accept/deny) out of
 * the url, as well as the request ID, and uses the friendStore to handle the action.
 * This way the friends are still sent back to the store, updated.
 * @param {string} url
 * @returns {Promise<boolean>}
 */
async function handleFriendRequests(url: string): Promise<boolean> {
    const action = url.split('/')[4];
    const requestId = parseInt(url.split('/')[3]);
    const friendStore = useFriendStore();
    let success = false;
    if (action == 'accept') success = await friendStore.acceptRequest(requestId);
    else if (action == 'deny') success = await friendStore.denyRequest(requestId);
    return success;
}

/**
 * Handles the group invites. If the group no longer exists, it will throw an error
 * accordingly, return a 'success' as true to ensure the link gets disabled. Any other
 * error will not cause the link to be disabled.
 * @param {string} url
 * @returns {Promise<boolean>}
 */
async function handleGroupInviteRequests(url: string): Promise<boolean> {
    let success = true;
    await axios.post(url).then().catch(e => {
        if (e.response.data.data.error === 'GROUP_DELETED') success = true;
        else success = false;
    });
    return success;
}

/**
 * Handles all other links as direct back-end calls as per their api type.
 */
async function handleOtherLinks(apiType: string, url: string): Promise<boolean> {
    const success = true;
    try {
        if (apiType == 'POST')
            await axios
                .post(url);
        else if (apiType == 'PUT')
            await axios
                .put(url);
        else if (apiType == 'DELETE')
            await axios
                .delete(url);
    } catch (e) {
        return false;
    }
    return success;
}
