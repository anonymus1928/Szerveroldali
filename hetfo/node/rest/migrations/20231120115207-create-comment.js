'use strict';
/** @type {import('sequelize-cli').Migration} */
module.exports = {
    async up(queryInterface, Sequelize) {
        await queryInterface.createTable('Comments', {
            id: {
                allowNull: false,
                autoIncrement: true,
                primaryKey: true,
                type: Sequelize.INTEGER,
            },
            text: {
                allowNull: false,
                type: Sequelize.STRING,
            },
            filename: {
                type: Sequelize.STRING,
            },
            UserId: {
                allowNull: false,
                onDelete: 'cascade',
                references: {
                    model: 'Users',
                    key: 'id',
                },
                type: Sequelize.INTEGER,
            },
            TicketId: {
                allowNull: false,
                onDelete: 'cascade',
                references: {
                    model: 'Tickets',
                    key: 'id',
                },
                type: Sequelize.INTEGER,
            },
            createdAt: {
                allowNull: false,
                type: Sequelize.DATE,
            },
            updatedAt: {
                allowNull: false,
                type: Sequelize.DATE,
            },
        });
    },
    async down(queryInterface, Sequelize) {
        await queryInterface.dropTable('Comments');
    },
};
