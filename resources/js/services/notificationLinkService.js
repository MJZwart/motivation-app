import axios from 'axios';
import {useFriendStore} from '/js/store/friendStore';

/**
 * Handles all notification links. Checks what the url type is (eg 'friend')
 * and handles accordingly. All other types are handled as direct back-end link
 * 
 * @param {string} apiType
 * @param {string} url
 * @return {Promise<boolean>}
 */
export async function handleNotificationLink(apiType, url) {
    const urlType = url.split('/')[1];
    let success = false;
    switch (urlType) {
        case 'friend':
            success = await handleFriendRequests(url);
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
 * 
 * @param {string} url
 * @returns {Promise<boolean>}
 */
async function handleFriendRequests(url) {
    const action = url.split('/')[4];
    const requestId = parseInt(url.split('/')[3]);
    const friendStore = useFriendStore();
    let success = false;
    if (action == 'accept')
        success = await friendStore.acceptRequest(requestId);
    else if (action == 'deny')
        success = await friendStore.denyRequest(requestId);
    return success;
}

/**
 * Handles all other links as direct back-end calls as per their api type.
 * 
 * @param {string} apiType
 * @param {string} url
 * @return {Promise<boolean>}
 */
async function handleOtherLinks(apiType, url) {
    let success = true;
    try {
        if (apiType == 'POST')
            await axios.post(url).then().catch(() => {
                success = false
            });
        else if (apiType == 'PUT')
            await axios.put(url).then().catch(() => success = false);
        else if (apiType == 'DELETE')
            await axios.delete(url).then().catch(() => success = false);
    } catch (e) {
        return false;
    }
    return success;
}


