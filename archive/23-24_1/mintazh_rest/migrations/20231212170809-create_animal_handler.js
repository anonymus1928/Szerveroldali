'use strict';

/** @type {import('sequelize-cli').Migration} */
module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.createTable('AnimalHandler', {
        id: {
            allowNull: false,
            autoIncrement: true,
            primaryKey: true,
            type: Sequelize.INTEGER,
        },
        AnimalId: {
            allowNull: false,
            type: Sequelize.INTEGER,
        },
        HandlerId: {
            allowNull: false,
            type: Sequelize.INTEGER,
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
    await queryInterface.addConstraint('AnimalHandler', {
        fields: ['AnimalId', 'HandlerId'],
        type: 'unique',
    })
  },

  async down (queryInterface, Sequelize) {
    await queryInterface.dropTable('AnimalHandler');
  }
};
