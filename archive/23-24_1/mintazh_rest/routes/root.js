const { StatusCodes } = require("http-status-codes");
const S = require("fluent-json-schema");
const db = require("../models");
const { Sequelize, sequelize } = db;
const { ValidationError, DatabaseError, Op } = Sequelize;
// TODO: Importáld a modelleket
const { Animal, Handler, Place } = db;

module.exports = function (fastify, opts, next) {
    // http://127.0.0.1:4000/
    fastify.get("/", async (request, reply) => {
        reply.send({ message: "Gyökér végpont" });
    });

    fastify.get(
        '/animals',
        async (request, reply) => {
            reply.send(await Animal.findAll());
        }
    );

    fastify.get(
        '/animals/:id',
        {
            schema: {
                params: {
                    type: 'object',
                    required: ['id'],
                    properties: {
                        id: { type: 'integer' },
                    }
                }
            }
        },
        async (request, reply) => {
            const { id } = request.params;

            const animal = await Animal.findByPk(id);

            if(!animal) {
                return reply.status(StatusCodes.NOT_FOUND).send();
            }

            reply.send(animal);
        }
    );

    fastify.post(
        '/animals',
        {
            schema: {
                body: {
                    type: 'object',
                    required: ['name', 'weight', 'birthdate', 'PlaceId'],
                    properties: {
                        name: { type: 'string' },
                        weight: { type: 'number' },
                        birthdate: { type: 'string', format: 'date' },
                        image: { type: 'string', nullable: true, default: null },
                        PlaceId: { type: 'integer' },
                    }
                }
            }
        },
        async (request, reply) => {
            reply.status(StatusCodes.CREATED).send(await Animal.create(request.body));
        }
    );

    fastify.patch(
        '/animals/:id',
        {
            schema: {
                params: {
                    type: 'object',
                    required: ['id'],
                    properties: {
                        id: { type: 'integer' },
                    }
                },
                body: {
                    type: 'object',
                    properties: {
                        name: { type: 'string' },
                        weight: { type: 'number' },
                        birthdate: { type: 'string', format: 'date' },
                        image: { type: 'string' },
                    }
                }
            }
        },
        async (request, reply) => {
            const { id } = request.params;

            const animal = await Animal.findByPk(id);

            if(!animal) {
                return reply.status(StatusCodes.NOT_FOUND).send();
            }

            await animal.update(request.body);
            await animal.reload();

            reply.send(animal);
        }
    );

    fastify.post(
        '/login',
        {
            schema: {
                body: {
                    type: 'object',
                    required: ['name'],
                    properties: {
                        name: { type: 'string' },
                    }
                }
            }
        },
        async (request, reply) => {
            const { name } = request.body;

            const handler = await Handler.findOne({
                where: {
                    name,
                }
            });

            if(!handler) {
                return reply.status(StatusCodes.NOT_FOUND).send();
            }

            const token = fastify.jwt.sign(handler.toJSON());

            reply.send({ token });
        }
    );

    fastify.get(
        '/my-animals',
        {
            onRequest: [fastify.auth],
        },
        async (request, reply) => {
            const handler = await Handler.findByPk(request.user.id);

            reply.send(await handler.getAnimals());
        }
    );

    fastify.get(
        '/my-animals/with-place',
        {
            onRequest: [fastify.auth],
        },
        async (request, reply) => {
            const handler = await Handler.findByPk(request.user.id);

            const animals = await Animal.findAll({
                include: [
                    {
                        model: Handler,
                        where: {
                            id: request.user.id,
                        },
                        attributes: [],
                    },
                    {
                        model: Place,
                    }
                ]
            });

            reply.send(animals);
        }
    );

    fastify.post(
        '/my-animals',
        {
            onRequest: [fastify.auth],
            schema: {
                body: {
                    type: 'object',
                    required: ['animals'],
                    properties: {
                        animals: { type: 'array' },
                    }
                }
            }
        },
        async (request, reply) => {
            const { animals } = request.body;

            const handler = await Handler.findByPk(request.user.id);

            const tmpReply = {
                invalidAnimals: [],
                alreadyMyAnimals: [],
                adoptedAnimals: []
            };

            for (const animalId of animals) {
                const animal = await Animal.findByPk(animalId);

                if (!animal) {
                    tmpReply.invalidAnimals.push(animalId);
                } else if (await handler.hasAnimal(animalId)) {
                    tmpReply.alreadyMyAnimals.push(animalId);
                } else {
                    await handler.addAnimal(animalId);
                    tmpReply.adoptedAnimals.push(animalId);
                }
            }

            reply.send(tmpReply);
        }
    );

    fastify.post(
        '/clean-places',
        {
            onRequest: [fastify.auth],
            schema: {
                body: {
                    type: 'object',
                    required: ['places'],
                    properties: {
                        places: { type: 'array' },
                    }
                }
            }
        },
        async (request, reply) => {
            const { places } = request.body;

            const handler = await Handler.findByPk(request.user.id);

            if (handler.power < 100) {
                return reply.status(StatusCodes.FORBIDDEN).send();
            }

            const tmpReply = {
                invalidPlaces: [],
                alreadyCleanPlaces: [],
                cleanedPlaces: [],
            };

            for (const placeId of places) {
                const place = await Place.findByPk(placeId);

                if (!place) {
                    tmpReply.invalidPlaces.push(placeId);
                } else if (place.cleaned) {
                    tmpReply.alreadyCleanPlaces.push(placeId);
                } else {
                    await place.update({ cleaned: true });
                    tmpReply.cleanedPlaces.push(placeId);
                }
            }

            reply.send(tmpReply);
        }
    );

    fastify.post(
        '/move-animals',
        {
            onRequest: [fastify.auth],
            schema: {
                body: {
                    type: 'array',
                }
            }
        },
        async (request, reply) => {
            const handler = await Handler.findByPk(request.user.id);

            if (handler.power < 100) {
                return reply.status(StatusCodes.FORBIDDEN).send();
            }

            const tmpReply = request.body;
            for (const item of tmpReply) {
                const animal = await Animal.findByPk(item.AnimalId || -1);
                const place = await Place.findByPk(item.PlaceId || -1);

                if(animal && place) {
                    if(await place.countAnimals() < place.capacity /*&& !await place.hasAnimal(animal.id)*/) {
                        item.success = true;
                        await animal.setPlace(place);
                    } else {
                        item.success = false;
                    }
                } else {
                    item.success = false;
                }
            }

            reply.send(tmpReply);
        }
    );


    // http://127.0.0.1:4000/auth-protected
    fastify.get("/auth-protected", { onRequest: [fastify.auth] }, async (request, reply) => {
        reply.send({ user: request.user });
    });

    next();
};

module.exports.autoPrefix = "/";
