const fastify = require('fastify')({
    logger: true,
});
const autoload = require('@fastify/autoload');
const { join } = require('path');

const secret = 'secret';

// Hitelesítés
fastify.register(require('@fastify/jwt'), {
    secret,
});

fastify.decorate('auth', async function (request, reply) {
    try {
        await request.jwtVerify();
    } catch (err) {
        reply.send(err);
    }
});

// Route-ok betöltése
fastify.register(autoload, {
    dir: join(__dirname, 'routes'),
});

// Declare a route
// fastify.get('/', async (request, reply) => {
//     reply.type('application/json').code(200);
//     reply.send({ hello: 'there' });
// });

// Run the server!
fastify.listen({ port: 4000 }, (err, address) => {
    if (err) throw err;
    // Server is now listening on ${address}
});
