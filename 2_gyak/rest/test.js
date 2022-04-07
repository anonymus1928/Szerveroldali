const models = require('./models');
const { User, Ticket, Comment } = models;
const { faker } = require('@faker-js/faker');

;(async () => {
    // const user = await User.create({
    //     name: faker.name.findName(),
    //     email: faker.internet.email(),
    //     password: 'password',
    //     isAdmin: faker.datatype.boolean(),
    // });
    // console.log(user);


    const userCount = faker.datatype.number({ min: 10, max: 20 });
    const users = [];
    for (let i = 0; i < userCount; i++) {
        users.push(await User.create({
            name: faker.name.findName(),
            email: faker.internet.email(),
            password: 'password',
            isAdmin: faker.datatype.boolean(),
        }));
    }

    /*
    TODO:
    Random Ticket db
    Mindegyik Ticket-hez kiválasztunk valamennyi user-t és generálunk commenteket
    */
})();