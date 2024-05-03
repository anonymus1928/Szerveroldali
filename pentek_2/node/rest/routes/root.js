

module.exports = function (fastify, opts, next) {
    fastify.get('/', function (request, reply) {
        reply.send({ general: 'kenobiiiiiiiiiiiii' });
    });
    
    next();
}
