import {TEST_USER_1, TEST_USER_2} from '../../support/commands';

describe('Groups', () => {
    
    function getRandomString(length = 5) {
        return Math.random().toString(36).substring(2, length + 2);
    }
    const publicGroupName = getRandomString();
    const groupWithApplicationName1 = getRandomString();
    const groupWithApplicationName2 = getRandomString();

    const user1 = TEST_USER_1;
    const user2 = TEST_USER_2;


    //User 1
    //Create group public                   publicGroupName
    //Edit group name                       publicGroupName (change back)
    //Create group public                   groupWithApplicationName1
    //Edit group to require application     groupWithApplicationName1
    //Create group requires application     groupWithApplicationName2

    //User 2
    //Join a public group                   publicGroupName
    //Send application                      groupWithApplicationName1
    //Send another application              groupWithApplicationName2

    //User 1
    //Can see application                   groupWithApplicationName1/groupWithApplicationName2
    //Can accept application                groupWithApplicationName1
    //Can reject application group 2        groupWithApplicationName2
    //Can send invite to friend (?)
    //Can send invite to a user             groupWithApplicationName2

    //User 2
    //User sees notification
    //User can accept the invite            groupWithApplicationName2
    //User is part of three groups          groupWithApplicationName1, groupWithApplicationName2, publicGroupName

    //User 1
    //Kick user from group                  groupWithApplicationName2
    //Send invite again                     groupWithApplicationName2

    //User 2
    //User can reject invite                groupWithApplicationName2
    //User can leave group                  groupWithApplicationName1

    //User 1
    //User can send another invite          groupWithApplicationName2
    //User can disband group                publicGroupName

});