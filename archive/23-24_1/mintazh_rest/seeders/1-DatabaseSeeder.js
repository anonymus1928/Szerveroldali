"use strict";

// Faker dokumentáció, API referencia: https://fakerjs.dev/guide/#node-js
const { faker } = require("@faker-js/faker");
const chalk = require("chalk");
// TODO: Importáld be a modelleket
const { Animal, Handler, Place } = require("../models");

module.exports = {
    up: async (queryInterface, Sequelize) => {
        // TODO: Ide dolgozd ki a seeder tartalmát:
        const places = [];
        const animals = [];
        const numberPlaces = faker.number.int({ min: 5, max: 10 });
        for (let i = 0; i < numberPlaces; i++) {
            const tmpCapacity  = faker.number.int({ min: 1, max: 10 });
            const tmpAnimals = faker.number.int({ min: 0, max: tmpCapacity });

            const place = await Place.create({
                cleaned: faker.datatype.boolean(),
                capacity: tmpCapacity,
            });
            places.push(place);

            for (let j = 0; j < tmpAnimals; j++) {
                animals.push(await Animal.create({
                    name: faker.person.firstName(),
                    weight: faker.number.float({ min: 1.0, max: 100.0, precision: 0.01 }),
                    birthdate: faker.date.birthdate({ min: 1, max: 20 }),
                    image: faker.datatype.boolean() ? faker.image.urlLoremFlickr({ category: 'animals' }) : null,
                    PlaceId: place.id,
                }));
            }
        }

        const handlers = [];
        const numberHandler = faker.number.int({ min: 3, max: 10 });
        for (let i = 0; i < numberHandler; i++) {
            handlers.push(await Handler.create({
                name: faker.person.fullName(),
                power: faker.number.int({ min: 1, max: 200 }),
            }));
        }

        for(const animal of animals) {
            await animal.addHandlers(faker.helpers.arrayElements(handlers, { min: 1, max: 3 }));
        }


        console.log(chalk.green("A DatabaseSeeder lefutott"));
    },

    // Erre alapvetően nincs szükséged, mivel a parancsok úgy vannak felépítve,
    // hogy tiszta adatbázist generálnak, vagyis a korábbi adatok enélkül is elvesznek
    down: async (queryInterface, Sequelize) => {},
};
