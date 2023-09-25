const models = require('./models');
const { User, Ticket, Comment, sequelize } = models;
const { faker } = require('@faker-js/faker');
const { Op } = require('sequelize');

/**
 * TODO: Seeder
 * Random számú user
 * Random számú ticket
 * Mindegyik tickethez vegyünk random valamennyi usert és azok közül random valamennyi komment
 */

(async () => {
    // const user = await User.create({
    //     name: faker.name.findName(),
    //     email: faker.internet.email(),
    //     password: 'password',
    //     isAdmin: faker.datatype.boolean()
    // });
    // console.log(user);

    // Userek
    // const userCount = faker.datatype.number({ min: 10, max: 20 });
    // const users = [];
    // for (let i = 0; i < userCount; i++) {
    //     users.push(
    //         await User.create({
    //             name: faker.name.findName(),
    //             email: faker.internet.email(),
    //             password: 'password',
    //             isAdmin: faker.datatype.boolean(),
    //         })
    //     );
    // }

    // Ticketek
    // const ticketCount = faker.datatype.number({ min: 15, max: 25 });
    // const tickets = [];
    // for (let i = 0; i < ticketCount; i++) {
    //     tickets.push(
    //         await Ticket.create({
    //             title: faker.lorem.words(faker.datatype.number({ min: 2, max: 6 })),
    //             priority: faker.datatype.number({ min: 0, max: 3 }),
    //             done: faker.datatype.boolean(),
    //         })
    //     );
    // }

    // for (const ticket of tickets) {
    //     const randUsers = faker.random.arrayElements(users);
    //     await ticket.setUsers(randUsers);

    //     for (const user of faker.random.arrayElements(randUsers)) {
    //         Comment.create({
    //             text: faker.lorem.paragraph(),
    //             UserId: user.id,
    //             TicketId: ticket.id,
    //         });
    //     }
    // }



    // console.log(await Ticket.findAll());
    // console.log(await Ticket.count());
    // console.log(await Ticket.findByPk(3));
    // console.log(await Ticket.findByPk(99999999));
    // console.log(await Ticket.findOne({ where: { id: 3 } }));

    // console.log(await Ticket.findAll({
    //     where: {
    //         priority: {
    //             [Op.gte]: 2
    //         }
    //     }
    // }));

    // console.log((await User.findByPk(2)).toJSON());

    // Összes priorításhoz mennyi ticket tartozik és mennyi comment.

    const prio = await Ticket.findAll({
        attributes: [
            'priority',
            [sequelize.fn('COUNT', sequelize.col('ticket.id')), 'numTicket'],
            [sequelize.fn('COUNT', sequelize.col('Comments.id')), 'numComment'],
        ],
        include: [
            {
                model: Comment,
                as: 'Comments',
                attributes: []
            },
            {
                model: User,
                as: 'Users',
            }
        ],
        group: ['priority'],
    });
    console.log(prio.map(p => p.toJSON()));
})();
