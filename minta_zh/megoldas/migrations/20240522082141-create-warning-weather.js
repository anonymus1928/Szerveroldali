"use strict";

/** @type {import('sequelize-cli').Migration} */
module.exports = {
    async up(queryInterface, Sequelize) {
        await queryInterface.createTable("WarningWeather", {
            id: {
                allowNull: false,
                autoIncrement: true,
                primaryKey: true,
                type: Sequelize.INTEGER,
            },
            WarningId: {
                allowNull: false,
                references: {
                    model: "warnings",
                    key: "id",
                },
                onDelete: "cascade",
                type: Sequelize.INTEGER,
            },
            WeatherId: {
                allowNull: false,
                references: {
                    model: "weather",
                    key: "id",
                },
                onDelete: "cascade",
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
        await queryInterface.dropTable("WarningWeather");
    },
};
