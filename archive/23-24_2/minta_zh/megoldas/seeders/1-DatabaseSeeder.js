"use strict";

// Faker dokumentáció, API referencia: https://fakerjs.dev/guide/#node-js
const { faker } = require("@faker-js/faker");
const chalk = require("chalk");
const { Warning, Weather, Location } = require("../models");

module.exports = {
    up: async (queryInterface, Sequelize) => {
        // Location
        const locationCount = faker.number.int({ min: 5, max: 10 });
        const locations = [];

        for (let i = 0; i < locationCount; i++) {
            locations.push(
                await Location.create({
                    name: faker.location.city(),
                    lat: faker.location.latitude(),
                    lon: faker.location.longitude(),
                    public: faker.datatype.boolean(),
                })
            );
        }
        console.log(chalk.green(`${locations.length} Location létrehozva.`));

        // Weather
        const weatherArray = [];
        for (const location of locations) {
            const weatherCount = faker.number.int({ min: 0, max: 5 });

            for (let i = 0; i < weatherCount; i++) {
                const weather = await Weather.create({
                    type: faker.lorem.word(),
                    LocationId: location.id,
                    temp: faker.number.float({ min: -20, max: 40, fractionDigits: 2 }),
                    loggedAt: faker.date.past({ years: 2 }),
                });
                weatherArray.push(weather);

                console.log(chalk.blue(`Location #${location.id} | ${weatherCount} Weather létrehozva.`));

                if (faker.datatype.boolean()) {
                    const warningCount = faker.number.int({ min: 1, max: 3 });
                    for (let j = 0; j < warningCount; j++) {
                        await weather.createWarning({
                            level: faker.number.int({ min: 1, max: 5 }),
                            message: faker.datatype.boolean() ? faker.lorem.sentence() : null,
                        });
                    }
                    console.log(chalk.red(`Weather #${weather.id} | ${warningCount} Warning létrehozva.`));
                }
            }

        }


        console.log(chalk.green("A DatabaseSeeder lefutott"));
    },

    // Erre alapvetően nincs szükséged, mivel a parancsok úgy vannak felépítve,
    // hogy tiszta adatbázist generálnak, vagyis a korábbi adatok enélkül is elvesznek
    down: async (queryInterface, Sequelize) => {},
};
