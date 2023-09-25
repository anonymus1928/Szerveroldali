'use strict';

module.exports = {
  async up (queryInterface, Sequelize) {
    await queryInterface.createTable('TicketUser', {
      id: {
        allowNull: false,
        autoIncrement: true,
        primaryKey: true,
        type: Sequelize.INTEGER
      },
      TicketId: {
        type: Sequelize.INTEGER
      },
      UserId: {
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

    await queryInterface.addConstraint('TicketUser', {
      fields: ['TicketId', 'UserId'],
      type: 'unique'
    });
  },

  async down (queryInterface, Sequelize) {
    await queryInterface.dropTable('TicketUser');
  }
};
