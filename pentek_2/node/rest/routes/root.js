/**
 * GET      /tickets       Összes ticket lekérdezése
 * GET      /tickets/:id   Ticket lekérdezése id alapján
 *
 * POST     /tickets       Ticket létrehozása
 *
 * PUT      /tickets       Ticket módosítása
 *
 * DELETE   /ticket        Ticket törlése
 *
 */

const { Ticket, Comment, User } = require('../models');
const { StatusCodes } = require('http-status-codes');

module.exports = function (fastify, opts, next) {
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
                    message: 'Ticket not found',
                });
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
                    required: ['title', 'priority', 'text'],
                    properties: {
                        title: { type: 'string' },
                        priority: { type: 'number', maximum: 3, minimum: 0 },
                        text: { type: 'string' },
                    },
                },
            },
        },
        async (request, reply) => {
            const { title, priority, text } = request.body;

            let ticket = await Ticket.create({
                title,
                priority,
            });
            await ticket.setUsers(request.user.payload.id);
            await ticket.createComment({ text, UserId: request.user.payload.id });

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
            onRequest: [fastify.auth],
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
                    required: ['title', 'priority', 'done'],
                    properties: {
                        title: { type: 'string' },
                        priority: { type: 'number', maximum: 3, minimum: 0 },
                        done: { type: 'boolean' },
                    },
                },
            },
        },
        async (request, reply) => {
            const { id } = request.params;
            const { title, priority, done } = request.body;

            let ticket = await Ticket.findByPk(id);

            if (!ticket) {
                return reply.status(StatusCodes.NOT_FOUND).send({
                    message: 'Ticket not found',
                });
            }

            ticket = await ticket.update({ title, priority, done });

            ticket = await Ticket.findByPk(id, {
                include: [
                    {
                        model: Comment,
                    },
                ],
            });

            reply.send(ticket);
        }
    );

    fastify.delete(
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

            if (!id) {
                const user = await User.findByPk(request.user.payload.id);
                if (user.is_admin) {
                    await Ticket.destroy({ where: {} });

                    return reply.status(StatusCodes.NO_CONTENT);
                } else {
                    return reply.status(StatusCodes.FORBIDDEN).send({
                        message: 'Only admins can delete everything!',
                    });
                }
            }

            let ticket = await Ticket.findByPk(id);

            if (!ticket) {
                return reply.status(StatusCodes.NOT_FOUND).send({
                    message: 'Ticket not found',
                });
            }

            await ticket.destroy();

            reply.status(StatusCodes.NO_CONTENT);
        }
    );

    next();
};
