const auth = require("./auth");
const db = require("../models");
const { reduce } = require("lodash");
const { Sequelize, sequelize } = db;
const { ValidationError, DatabaseError, Op } = Sequelize;
const { Location, Warning, Weather } = db;

module.exports = {
    Query: {
        // Elemi Hello World! példa:
        helloWorld: () => "Hello World!",

        // Példa paraméterezésre:
        helloName: (_, { name }) => `Hello ${name}!`,

        // 11. feladat
        locations: async () => Location.findAll(),
        weather: async () => Weather.findAll(),

        // 12. feladat
        location: async (_, { id }) => Location.findByPk(id),

        // 18. feladat
        statistics: async () => ({
            locationCount: await Location.count(),
            averageTemp: (await Weather.findAll()).reduce((s, w) => w.temp + s, 0) / (await Weather.count()),
            over30Celsius: await Weather.count({ where: { temp: { [Op.gt]: 30 } } }),
            multipleWarnings: (await Weather.findAll({ include: Warning })).filter((weather) => weather.Warnings.length >= 2).length,
            mostActiveLocation: (await Location.findAll( { include: Weather, order: [['id', 'ASC']] } )).reduce((r, l) => l.Weather.length > r.Weather.length ? l : r, { Weather: [] }),
        }),
    },

    Location: {
        // 16. feladat
        currentTemp: async (location) =>
            (await Weather.findOne({ where: { LocationId: location.id }, order: [["loggedAt", "DESC"]] }))?.temp,
    },

    Weather: {
        // 13. feladat
        location: async (weather) => weather.getLocation(),

        // 15. feladat
        warnings: async (weather) => weather.getWarnings({ order: [["level", "DESC"]] }),
    },

    Mutation: {
        // 14. feladat
        createWeather: async (_, { input }) => Weather.create(input),

        // 17. feladat
        setPublic: async (_, { LocationId, public }) => {
            const location = await Location.findByPk(LocationId);

            if (!location) {
                return 'NOT FOUND';
            }

            if (location.public === public) {
                return 'ALREADY SET';
            }

            await location.update({ public });

            return 'CHANGED';
        }
    },
};
