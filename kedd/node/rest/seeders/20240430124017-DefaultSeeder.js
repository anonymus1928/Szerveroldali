'use strict';
const { faker } = require('@faker-js/faker');
const { User, Ticket, Comment } = require('../models');

/** @type {import('sequelize-cli').Migration} */
module.exports = {
    async up(queryInterface, Sequelize) {
        // User
        const userCount = faker.number.int({ min: 10, max: 20 });
        const users = [];
        users.push(
            await User.create({
                name: 'Super Admin',
                email: 'q@q.hu',
                password: 'password',
                is_admin: true,
            })
        );
        for (let i = 0; i < userCount - 1; i++) {
            users.push(
                await User.create({
                    name: faker.person.fullName(),
                    email: `q${i}@q.hu`,
                    password: 'password',
                    is_admin: faker.datatype.boolean(),
                })
            );
        }

        // Ticket
        const ticketCount = faker.number.int({ min: 15, max: 25 });
        const tickets = [];
        for (let i = 0; i < ticketCount; i++) {
            tickets.push(
                await Ticket.create({
                    title: faker.lorem.sentence(),
                    priority: faker.number.int({ min: 0, max: 3 }),
                    done: faker.datatype.boolean(),
                })
            );
        }

        // Ticket - User
        for (const ticket of tickets) {
            const randUsers = faker.helpers.arrayElements(users);
            await ticket.setUsers(randUsers);

            for (const user of faker.helpers.arrayElements(randUsers)) {
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
