/**
 * GET     /tickets      Összes ticket lekérdezése
 * GET     /tickets/:id  Egy ticket lekérdezése
 *
 * POST    /tickets      Ticket létrehozása
 *
 * PUT     /tickets/:id  Ticket szerkesztése
 *
 * DELETE  /tickets/:id  Ticket törlése
 */

const { Ticket, Comment } = require('../models');
const { StatusCodes } = require('http-status-codes');

module.exports = function (fastify, opts, next) {
    fastify.get(
        '/tickets/:id?',
        {
            schema: {
                params: {
                    type: 'object',
                    properties: {
                        id: { type: 'number' },
                    },
                },
            },
            onRequest: [fastify.auth],
        },
        async (request, reply) => {
            const { id } = request.params;

            let tickets = null;

            if (!id) {
                tickets = await Ticket.findAll({
                    include: [
                        {
                            model: Comment,
                        },
                    ],
                });
            } else {
                tickets = await Ticket.findByPk(id, {
                    include: [
                        {
                            model: Comment,
                        },
                    ],
                });
            }

            if (!tickets) {
                return reply.status(StatusCodes.NOT_FOUND).send({
                    message: 'Ticket not found.',
                });
            }

            reply.send(tickets);
        }
    );

    fastify.post(
        '/tickets',
        {
            schema: {
                body: {
                    type: 'object',
                    required: ['title', 'priority', 'text'],
                    properties: {
                        title: { type: 'string' },
                        priority: { type: 'number' },
                        text: { type: 'string' },
                    },
                },
            },
            onRequest: [fastify.auth],
        },
        async (request, reply) => {
            const { title, priority, text } = request.body;

            let ticket = await Ticket.create({ title, priority });
            ticket.createComment({ text, UserId: request.user.payload.id });

            ticket = await Ticket.findByPk(ticket.id, {
                include: [
                    {
                        model: Comment,
                    },
                ],
            });

            reply.status(StatusCodes.CREATED).send(ticket);
        }
    );

    fastify.put(
        '/tickets/:id',
        {
            schema: {
                params: {
                    type: 'object',
                    required: ['id'],
                    properties: {
                        id: { type: 'number' },
                    },
                },
                body: {
                    type: 'object',
                    properties: {
                        title: { type: 'string' },
                        priority: { type: 'number' },
                        done: { type: 'boolean' },
                    },
                },
            },
            onRequest: [fastify.auth],
        },
        async (request, reply) => {
            const { id } = request.params;
            const { title, priority, done } = request.body;

            let ticket = await Ticket.findByPk(id);

            if (!ticket) {
                return reply.status(StatusCodes.NOT_FOUND).send({
                    message: 'Ticket not found.',
                });
            }

            await ticket.update({ title, priority, done });

            reply.status(StatusCodes.CREATED).send(ticket);
        }
    );

    fastify.delete(
        '/tickets/:id?',
        {
            schema: {
                params: {
                    type: 'object',
                    properties: {
                        id: { type: 'number' },
                    },
                },
            },
            onRequest: [fastify.auth],
        },
        async (request, reply) => {
            const { id } = request.params;

            if (!id) {
                await Ticket.destroy({ where: {} });

                return reply.status(204);
            }

            let ticket = await Ticket.findByPk(id);

            if (!ticket) {
                return reply.status(StatusCodes.NOT_FOUND).send({
                    message: 'Ticket not found.',
                });
            }

            await ticket.destroy();

            reply.status(204);
        }
    );

    next();
};
