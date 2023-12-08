const { Ticket, Comment } = require('./../models');
const { StatusCodes } = require('http-status-codes');

/**
 *
 * GET    /tickets       Összes ticket lekérdezése
 * GET    /tickets/:id   Megadott ID-jú ticket lekérdezése
 *
 * POST   /tickets       Új ticket létrehozása
 *
 * PUT    /tickets/:id   Ticket módosítása
 *
 * DELETE /tickets/:id   Ticket törlése
 *
 */

module.exports = function (fastify, opts, next) {
    fastify.get('/', async (request, reply) => {
        return { hello: 'thereeeeeeeeeeeeee3333333333333' };
    });

    fastify.get(
        '/tickets/:id?',
        {
            onRequest: [fastify.auth],
            schema: {
                params: {
                    type: 'object',
                    properties: {
                        id: { type: 'number' },
                    },
                },
            },
        },
        async (request, reply) => {
            const { id } = request.params;
            let tickets = null;
            if (!id) {
                tickets = await Ticket.findAll({ include: [{ model: Comment }] });
            } else {
                tickets = await Ticket.findByPk(id, { include: [{ model: Comment }] });
            }
            if (!tickets) {
                return reply.status(StatusCodes.NOT_FOUND).send({ message: 'Ticket not found!' });
            }
            reply.send(tickets);
        }
    );

    fastify.post(
        '/tickets',
        {
            onRequest: [fastify.auth],
            schema: {
                body: {
                    type: 'object',
                    properties: {
                        title: { type: 'string' },
                        priority: { type: 'number' },
                        text: { type: 'string' },
                    },
                },
            },
        },
        async (request, reply) => {
            const { title, priority, text } = request.body;

            let ticket = await Ticket.create({ title, priority });

            await ticket.createComment({
                text,
                UserId: request.user.payload.id,
            });

            ticket = await Ticket.findByPk(ticket.id, { include: [{ model: Comment }] });

            reply.send(ticket);
        }
    );

    fastify.put(
        '/tickets/:id',
        {
            onRequest: [fastify.auth],
            schema: {
                body: {
                    type: 'object',
                    properties: {
                        title: { type: 'string' },
                        priority: { type: 'number' },
                        done: { type: 'string' },
                    },
                },
                params: {
                    type: 'object',
                    properties: {
                        id: { type: 'number' },
                    },
                },
            },
        },
        async (request, reply) => {
            const { title, priority, done } = request.body;
            const { id } = request.params;

            let ticket = await Ticket.findByPk(id);

            if (!ticket) {
                return reply.status(StatusCodes.NOT_FOUND).send({ message: 'Ticket not found!' });
            }

            ticket = await ticket.update({ title, priority, done });

            reply.send(ticket);
        }
    );

    fastify.delete(
        '/tickets/:id',
        {
            onRequest: [fastify.auth],
            schema: {
                params: {
                    type: 'object',
                    properties: {
                        id: { type: 'number' },
                    },
                },
            },
        },
        async (request, reply) => {
            const { id } = request.params;

            const ticket = await Ticket.findByPk(id);

            if (!ticket) {
                return reply.status(StatusCodes.NOT_FOUND).send({ message: 'Ticket not found!' });
            }

            await ticket.destroy();

            reply.status(StatusCodes.NO_CONTENT);
        }
    );

    next();
};
