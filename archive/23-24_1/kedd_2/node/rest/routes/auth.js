const { User } = require('../models');
const { StatusCodes } = require('http-status-codes');

module.exports = function (fastify, opts, next) {
    fastify.post(
        '/login',
        {
            schema: {
                body: {
                    type: 'object',
                    required: ['email', 'password'],
                    properties: {
                        email: { type: 'string' },
                        password: { type: 'string' },
                    },
                },
            },
        },
        async (request, reply) => {
            const { email, password } = request.body;

            const user = await User.findOne({
                where: {
                    email,
                },
            });

            if (!user) {
                return reply.status(StatusCodes.NOT_FOUND).send({
                    message: 'User not found.',
                });
            }

            if (!user.comparePassword(password)) {
                return reply.status(StatusCodes.FORBIDDEN).send({
                    message: 'Wrong password.',
                });
            }

            const token = fastify.jwt.sign({
                payload: user,
            });

            reply.send({ token });
        }
    );

    fastify.get(
        '/who',
        {
            onRequest: [fastify.auth],
        },
        async (request, reply) => {
            reply.send(request.user);
        }
    );

    fastify.post(
        '/register',
        {
            schema: {
                body: {
                    type: 'object',
                    required: ['name', 'email', 'password'],
                    properties: {
                        name: { type: 'string' },
                        email: { type: 'string' },
                        password: { type: 'string' },
                    },
                },
            },
        },
        async (request, reply) => {
            const { name, email, password } = request.body;

            let user = await User.findOne({
                where: {
                    email,
                },
            });

            if (user) {
                return reply.status(StatusCodes.BAD_REQUEST).send({
                    message: 'Email must be unique.',
                });
            }

            user = await User.create({ name, email, password });

            const token = fastify.jwt.sign({
                payload: user,
            });

            reply.send({ token });
        }
    );

    next();
};
