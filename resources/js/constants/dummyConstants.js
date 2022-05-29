export const DUMMY_TASK_LIST = {
    name: 'Tasks',
    tasks: [
        {id: 0, name: 'Take the stairs', description: 'Take the stairs instead of the elevator.'},
        {
            id: 1, 
            name: 'Weekly clean up', 
            description: 'Clean your home/room, water your plants, take out the trash, etc.',
            tasks: [
                {id: 2, name: 'Clean the desk', description: ''},
                {id: 3, name: 'Vacuum the living room', description: ''}],
        },
        {
            id: 4, 
            name: 'Hang out with a friend', 
            description: 'Rather than staying home all day, go meet up with a friend for a lovely walk through town',
        },
        {
            id: 5,
            name: 'Grocery shopping',
            description: 'Shopping list is on the counter. Don\'t forget the bread!',
        },
    ],
};

export const DUMMY_CHARACTER = {
    name: 'Dummy thicc', 
    a: 1,
    a_exp: 100,
    a_exp_needed: 1000,
    b: 2,
    b_exp: 150,
    b_exp_needed: 1050,
    c: 1,
    c_exp: 850,
    c_exp_needed: 1000,
    d: 4,
    d_exp: 450,
    d_exp_needed: 1200,
    e: 3,
    e_exp: 800,
    e_exp_needed: 1150,
    experience: 500,
    level: 1,
    level_exp_needed: 1000,
};