const auth = require('./auth');
const { User, Ticket, Comment } = require('../models');

module.exports = {
    Query: {
        hello: async (parent, params, context, info) => `Hello ${params.name}!`,
        helloo: async (parent, params, context, info) => console.log(parent, params, context, info),
        add: async (_, { x, y }) => x + y,

        who: auth((parent, params, context) => `Hello ${context.user.name}`),

        users: auth(async () => await User.findAll()),
        user: auth(async (_, { id }) => await User.findByPk(id)),

        tickets: auth(async () => await Ticket.findAll()),
        ticket: auth(async (_, { id }) => await Ticket.findByPk(id)),

        comments: auth(async () => await Comment.findAll()),
        comment: auth(async (_, { id }) => await Comment.findByPk(id)),
    },

    User: {
        comments: auth(async user => await user.getComments()),
        tickets: auth(async user => await user.getTickets()),
    },

    Ticket: {
        comments: auth(async ticket => await ticket.getComments()),
        users: auth(async ticket => await ticket.getUsers()),
    },

    Comment: {
        user: auth(async comment => await comment.getUser()),
        ticket: auth(async comment => await comment.getTicket()),
    },

    Mutation: {
        createTicket: auth(async (_, { title, priority, text }, context) => {
            if (![0, 1, 2, 3].includes(priority)) {
                throw Error('Wrong priority!');
            }

            const ticket = await Ticket.create({ title, priority });
            await ticket.addUser(context.user.id);
            await ticket.createComment({ text, UserId: context.user.id });

            await ticket.reload();
            
            return ticket;
        }),
    },
};
