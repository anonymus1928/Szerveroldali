'use strict';
const faker = require('faker');
const models = require('../models'); // index.js
const { User, Genre, Movie, Rating } = models;

module.exports = {
  up: async (queryInterface, Sequelize) => {
    // Users
    const usersCount = faker.datatype.number({min: 15, max: 30});
    const users = [];
    users.push(
        User.create({
            name: 'Admin',
            email: 'q@q.hu',
            password: 'password',
            isAdmin: true
        })
    );
    for(let i = 1; i < usersCount; i++) {
        users.push(
            User.create({
                name: faker.name.findName(),
                email: `q${i}@q.hu`,
                password: 'password',
                isAdmin: false
            })
        )
    }
  },

  down: async (queryInterface, Sequelize) => {
    /**
     * Add commands to revert seed here.
     *
     * Example:
     * await queryInterface.bulkDelete('People', null, {});
     */
  }
};
