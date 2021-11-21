'use strict';

module.exports = {
  up: async (queryInterface, Sequelize) => {
    await queryInterface.createTable('GenreMovie', {
        id: {
          allowNull: false,
          autoIncrement: true,
          primaryKey: true,
          type: Sequelize.INTEGER
        },
        GenreId: {
          type: Sequelize.INTEGER
        },
        MovieId: {
          type: Sequelize.INTEGER
        },
        createdAt: {
          allowNull: false,
          type: Sequelize.DATE
        },
        updatedAt: {
          allowNull: false,
          type: Sequelize.DATE
        }
      });

    // Megkötés: ne lehessen többször hozzáadni ugyanazt a genre-movie párost.
    await queryInterface.addConstraint('GenreMovie', {
        fields: ['GenreId', 'MovieId'],
        type: 'unique',
    })
  },

  down: async (queryInterface, Sequelize) => {
    await queryInterface.dropTable('GenreMovie');
  }
};
