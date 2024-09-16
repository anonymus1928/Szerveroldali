// CommonJs
const fastify = require('fastify')({
    logger: true,
});
const autoload = require('@fastify/autoload');
const { readdirSync } = require('fs');
const { request } = require('http');
const mercurius = require('mercurius');
const { join } = require('path');
const registerGraphQL = require('./graphql');
require('dotenv').config();

const port = process.env.PORT || 4000;
const secret = process.env.SECRET || 'secret';

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

// Route-ok automatikus betöltése
fastify.register(autoload, {
    dir: join(__dirname, 'routes'),
});

// GraphQL
registerGraphQL(fastify);

// Run the server!
fastify.listen({ port }, function (err, address) {
    if (err) throw err;

    console.log(`A Fastify app fut: ${address}`);
});
