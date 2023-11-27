'use strict';

const faker = require('@faker-js/faker');
const models = require('../models');
const { User, Ticket, Comment } = models;

/** @type {import('sequelize-cli').Migration} */
module.exports = {
    async up(queryInterface, Sequelize) {
        // User
        const userCount = faker.datatype.number({ min: 10, max: 20 });
        const users = [];

        users.push(
            await User.create({
                name: 'Super Admin',
                email: 'q@q.hu',
                password: 'passwordEZCSAKPLACEHOLDER',
                is_admin: true,
            })
        );

        for (let i = 0; i < userCount - 1; i++) {
            users.push(
                await User.create({
                    name: faker.name.fullName(),
                    email: fakerSR_RS_latin.internet.email(),
                    password: 'passwordEZCSAKPLACEHOLDER',
                    is_admin: faker.datatype.boolean(),
                })
            );
        }

        // Ticket
        const ticketCount = faker.datatype.number({ min: 15, max: 25 });
        const tickets = [];
        for (let i = 0; i < ticketCount; i++) {
            tickets.push(
                await Ticket.create({
                    title: faker.lorem.sentence(),
                    // priority: faker.datatype.number({ min: 0, max: 3 }),
                    priority: 2,
                    done: faker.datatype.boolean(),
                })
            );
        }

        // Ticket - User
        for (const ticket of tickets) {
            const randUser = faker.helpers.arrayElements(users);
            await ticket.setUsers(randUser);

            for (const user of faker.helpers.arrayElements(randUser)) {
                await Comment.create({
                    text: faker.lorem.paragraph(),
                    UserId: user.id,
                    TicketId: ticket.id,
                });
            }
        }
    },

    async down(queryInterface, Sequelize) {
        /**
         * Add commands to revert seed here.
         *
         * Example:
         * await queryInterface.bulkDelete('People', null, {});
         */
    },
};
