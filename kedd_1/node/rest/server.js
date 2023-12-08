const fastify = require('fastify')({
    logger: true,
});
const autoload = require('@fastify/autoload');
const { join } = require('path');
const mercurius = require('mercurius');
const { readFileSync } = require('fs');

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

// GraphQL
fastify.register(mercurius, {
    schema: readFileSync('./graphql/schema.gql').toString(),
    resolvers: require('./graphql/resolvers'),
    graphiql: true,
    context: request => request,
});

// Run the server!
fastify.listen({ port: 4000 }, (err, address) => {
    if (err) throw err;
    // Server is now listening on ${address}
});
