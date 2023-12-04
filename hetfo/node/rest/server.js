const fastify = require('fastify')({ logger: true });
const autoload = require('@fastify/autoload');
const { join } = require('path');

const secret = 'supersecretsalt';

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

/**
 * Run the server!
 */
const start = async () => {
    try {
        await fastify.listen({ port: 4000 });
    } catch (err) {
        fastify.log.error(err);
        process.exit(1);
    }
};
start();
