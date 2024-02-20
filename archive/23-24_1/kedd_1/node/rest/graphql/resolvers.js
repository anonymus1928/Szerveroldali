const auth = require('./auth');
const { User, Ticket, Comment } = require('../models');

module.exports = {
    Query: {
        hello: async () => 'Hello there!',
        helloName: async (parent, params, context, info) => {
            // console.log(context);
            return `Hello ${params.name}`;
        },
        add: async (_, { x, y }) => x + y,

        who: auth(async (parent, params, context) => `Hello ${context.user.name}`),

        users: async () => User.findAll(),
        user: async (_, { id }) => User.findByPk(id),

        tickets: async () => Ticket.findAll(),
        ticket: async (_, { id }) => Ticket.findByPk(id),

        comments: async () => Comment.findAll(),
        comment: async (_, { id }) => Comment.findByPk(id),
    },

    User: {
        tickets: async (user) => user.getTickets(),
        comments: async (user) => user.getComments(),
    },

    Ticket: {
        users: async (ticket) => ticket.getUsers(),
        comments: async (ticket) => ticket.getComments(),
    },

    Comment: {
        ticket: async (comment) => comment.getTicket(),
        user: async (comment) => comment.getUser(),
    },

    Mutation: {
        createTicket: auth(async (_, { title, priority, text }, context) => {
            if (![0,1,2,3].includes(priority)) {
                throw new Error('Wrong priority!');
            }

            const ticket = await Ticket.create({ title, priority });
            await ticket.addUser(context.user.id);
            await ticket.addComment({ text, UserId: context.user.id });
            await ticket.reload();

            return ticket;
        }),
        deleteTicket: auth(async (_, { id }, context) => {
            const ticket = await Ticket.findByPk(id);
            
            if (!ticket) {
                throw new Error('Not found!');
            }

            await ticket.destroy();

            return true;
        }),
    }
};
