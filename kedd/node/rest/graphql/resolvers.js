const auth = require('./auth');
const { User, Ticket, Comment } = require('../models');

module.exports = {
    Query: {
        hello: () => 'Hello there!',
        helloName: (_, { name }) => {
            return `Hello ${name}!`;
        },
        add: (_, { x, y }) => x + y,

        who: auth(async (parent, params, context) => {
            return `Hello ${context.user.payload.name}!`;
        }),

        users: async () => User.findAll(),
        user: async (_, { id }) => User.findByPk(id),

        tickets: async () => Ticket.findAll(),
        ticket: async (_, { id }) => Ticket.findByPk(id),

        comments: async () => Comment.findAll(),
        comment: async (_, { id }) => Comment.findByPk(id),
    },

    User: {
        tickets: async user => user.getTickets(),
        comments: async user => user.getComments(),
    },

    Ticket: {
        users: async ticket => ticket.getUsers(),
        comments: async ticket => ticket.getComments(),
    },

    Comment: {
        ticket: async comment => comment.getTicket(),
        user: async comment => comment.getUser(),
    },

    Mutation: {
        createTicket: auth(async (_, { title, priority, text }, context) => {
            if (!title || isNaN(priority) || !text) {
                throw new Error('Title, priority and text is required!');
            }

            if (![0, 1, 2, 3].includes(priority)) {
                throw new Error('Wrong priority!');
            }

            const ticket = await Ticket.create({
                title,
                priority,
            });
            ticket.setUsers(context.user.payload.id);
            ticket.createComment({ text, UserId: context.user.payload.id });

            await ticket.reload();

            return ticket;
        }),

        deleteTicket: auth(async (_, { id }, context) => {
            const ticket = await Ticket.findByPk(id);
            const user = await User.findByPk(context.user.payload.id);

            if (!user.is_admin) {
                throw new Error('Unauthorized!');
            }

            if (!ticket) {
                throw new Error('Ticket not found!');
            }

            await ticket.destroy();

            return true;
        }),
    },
};
