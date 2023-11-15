const fastify = require('fastify')({
    logger: true,
});

// Declare a route
fastify.get('/', async (request, reply) => {
    reply.type('application/json').code(200);
    reply.send({ hello: 'there' });
});

// Run the server!
fastify.listen({ port: 3000 }, (err, address) => {
    if (err) throw err;
    // Server is now listening on ${address}
});
