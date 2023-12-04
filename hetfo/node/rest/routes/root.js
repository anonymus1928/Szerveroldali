const { Ticket } = require('./../models');
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
                        id: { type: 'number' }
                    }
                }
            }
        },
        async (request, reply) => {
            const { id } = request.params;
            let tickets = null;
            if (!id) {
                tickets = await Ticket.findAll();
            } else {
                tickets = await Ticket.findByPk(id);
            }
            if (!tickets) {
                return reply.status(StatusCodes.NOT_FOUND).send({ message: 'Ticket not found!' });
            }
            reply.send(tickets);
        }
    )

    next();
};
