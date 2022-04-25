'use strict';

const { faker } = require("@faker-js/faker");
const models = require('../models');
const { User, Ticket, Comment } = models;

module.exports = {
  async up (queryInterface, Sequelize) {
    // Users
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

    // Tickets
    const ticketCount = faker.datatype.number({ min: 15, max: 25 });
    const tickets = [];
    for (let i = 0; i < ticketCount; i++) {
        tickets.push(await Ticket.create({
            title: faker.lorem.words(faker.datatype.number({ min: 2, max: 6 })),
            priority: faker.datatype.number({ min: 0, max: 3 }),
            done: faker.datatype.boolean(),
        }));
    }

    for (const ticket of tickets) {
      const randUsers = faker.random.arrayElements(users);
      await ticket.setUsers(randUsers);

      for (const user of faker.random.arrayElements(randUsers)) {
        Comment.create({
          text: faker.lorem.paragraph(),
          UserId: user.id,
          TicketId: ticket.id,
        });
      }
    }
  },

  async down (queryInterface, Sequelize) {
    /**
     * Add commands to revert seed here.
     *
     * Example:
     * await queryInterface.bulkDelete('People', null, {});
     */
  }
};
