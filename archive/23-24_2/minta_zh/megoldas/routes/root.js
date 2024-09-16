const { StatusCodes } = require("http-status-codes");
const S = require("fluent-json-schema");
const db = require("../models");
const { Sequelize, sequelize } = db;
const { ValidationError, DatabaseError, Op } = Sequelize;
const { Location, Weather, Warning } = db;

module.exports = function (fastify, opts, next) {
    // http://127.0.0.1:4000/
    fastify.get("/", async (request, reply) => {
        reply.send({ message: "Gyökér végpont" });

        // NOTE: A send alapból 200 OK állapotkódot küld, vagyis az előző sor ugyanaz, mint a következő:
        // reply.status(200).send({ message: "Gyökér végpont" });

        // A 200 helyett használhatsz StatusCodes.OK-ot is (így szemantikusabb):
        // reply.status(StatusCodes.OK).send({ message: "Gyökér végpont" });
    });

    // http://127.0.0.1:4000/auth-protected
    fastify.get("/auth-protected", { onRequest: [fastify.auth] }, async (request, reply) => {
        reply.send({ user: request.user });
    });

    // 3. feladat
    fastify.get("/locations", async (request, reply) => {
        reply.send(await Location.findAll());
    });

    // 4. feladat
    fastify.get("/locations/:id",
    {
        schema: {
            params: {
                type: 'object',
                required: ['id'],
                properties: {
                    id: { type: 'integer' }
                }
            }
        }
    },
    async (request, reply) => {
        const { id } = request.params;
        const location = await Location.findByPk(id);

        if (!location) {
            return reply.status(StatusCodes.NOT_FOUND).send();
        }

        reply.send(location);
    });

    // 5. feladat
    fastify.post("/locations",
    {
        schema: {
            body: {
                type: 'object',
                required: ['name', 'lon', 'lat'],
                properties: {
                    name: { type: 'string' },
                    lon: { type: 'number' },
                    lat: { type: 'number' },
                    public: { type: 'boolean', default: true },
                }
            }
        }
    },
    async (request, reply) => {
        const { name, lon, lat, public } = request.body;

        const location = await Location.create({
            name,
            lat,
            lon,
            public,
        });

        reply.status(StatusCodes.CREATED).send(location);
    });

    // 6. feladat
    fastify.delete("/locations/:id",
    {
        schema: {
            params: {
                type: 'object',
                required: ['id'],
                properties: {
                    id: { type: 'integer' }
                }
            }
        }
    },
    async (request, reply) => {
        const { id } = request.params;
        const location = await Location.findByPk(id);

        if (!location) {
            return reply.status(StatusCodes.NOT_FOUND).send();
        }

        await location.destroy();

        reply.send();
    });

    // 7. feladat
    fastify.post("/login",
    {
        schema: {
            body: {
                type: 'object',
                required: ['email'],
                properties: {
                    email: { type: 'string' },
                }
            }
        }
    },
    async (request, reply) => {
        const { email } = request.body;

        // locationX@weather.org
        const regex = /^location([0-9]+)@weather\.org$/;
        const resp = email.match(regex);

        if (!resp) {
            return reply.status(StatusCodes.IM_A_TEAPOT).send();
        }

        const location = await Location.findByPk(resp[1]);

        if (!location) {
            return reply.status(StatusCodes.NOT_FOUND).send();
        }

        reply.send({ token: fastify.jwt.sign(location.toJSON()) });
    });

    // 8. feladat
    fastify.get("/local-weather-log",
    {
        onRequest: [ fastify.auth ],
    },
    async (request, reply) => {
        reply.send(await Weather.findAll({ where: { LocationId: request.user.id }, order: [['loggedAt', 'ASC']] }));
    });

    // 9. feladat
    fastify.post("/insert-many",
    {
        onRequest: [ fastify.auth ],
        schema: {
            body: {
                type: 'object',
                required: ['type', 'startTime', 'interval', 'temps'],
                properties: {
                    type: { type: 'string' },
                    startTime: { type: 'string', format: 'date-time' },
                    interval: { type: 'integer' },
                    temps: { type: 'array', items: { type: 'number' } },
                }
            }
        }
    },
    async (request, reply) => {
        const { type, startTime, interval, temps } = request.body;
        const result = [];

        for (const temp of temps) {
            result.push(
                await Weather.create({
                    type,
                    LocationId: request.user.id,
                    temp,
                    loggedAt: new Date(startTime).getTime() + interval * result.length * 60000,
                })
            );
        }

        reply.send(result);
    });

    // 10. feladat
    fastify.post("/issue-warning",
    {
        onRequest: [ fastify.auth ],
        schema: {
            body: {
                type: 'object',
                required: ['WeatherId', 'WarningId'],
                properties: {
                    WeatherId: { type: 'integer' },
                    WarningId: { type: 'integer' },
                }
            }
        }
    },
    async (request, reply) => {
        const { WeatherId, WarningId } = request.body;
        const weather = await Weather.findByPk(WeatherId);
        const warning = await Warning.findByPk(WarningId);

        if (!weather || !warning) {
            return reply.status(StatusCodes.NOT_FOUND).send();
        }

        if (weather.LocationId !== request.user.id) {
            return reply.status(StatusCodes.FORBIDDEN).send();
        }

        if (await weather.hasWarning(WarningId)) {
            return reply.status(StatusCodes.CONFLICT).send();
        }

        await weather.addWarning(WarningId);

        return reply.status(StatusCodes.CREATED).send();
    });


    next();
};

module.exports.autoPrefix = "/";
