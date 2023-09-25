const models = require('./models');
const { User, Ticket, Comment, sequelize } = models;
const { faker } = require('@faker-js/faker');

(async () => {
    // const user = await User.create({
    //     name: faker.name.findName(),
    //     email: faker.internet.email(),
    //     password: 'password',
    //     isAdmin: faker.datatype.boolean(),
    // });
    // console.log(user);

    // const userCount = faker.datatype.number({ min: 10, max: 20 });
    // const users = [];
    // for (let i = 0; i < userCount; i++) {
    //     users.push(await User.create({
    //         name: faker.name.findName(),
    //         email: faker.internet.email(),
    //         password: 'password',
    //         isAdmin: faker.datatype.boolean(),
    //     }));
    // }

    /*
    TODO:
    Random Ticket db
    Mindegyik Ticket-hez kiválasztunk valamennyi user-t és generálunk commenteket
    */


    // Az egyes prioritásokhoz mennyi ticket és mennyi comment tartozik?
    const asdf = await Ticket.findAll({
        attributes: [
            'priority',
            [sequelize.fn('COUNT', sequelize.col('ticket.id')), 'ticketsNum'],
            [sequelize.fn('COUNT', sequelize.col('Comments.id')), 'commentsNum'],
        ],
        include: [
            {
                model: Comment,
                as: 'Comments',
                attributes: [], // semmit sem szeretnék
                // attributes: [...], // csak ezeket szeretném
                // attributes: { exclude: [...] }, // ezeket kivéve mindent szeretnék
            },
        ],
        group: ['ticket.priority'],
    });
    console.log(asdf.map(a => a.toJSON()));

})();
