const models = require('../models');
const authMW = require('../middlewares/auth');
const user = require('../models/user');
const { Ticket, Comment, User, sequelize } = models;

// Visszaad egy async wrapper fv-t, ami megkap minden lehetséges paramétert.
// Ez az async fv meghívja az eredeti fn-t, amit az auth-nak adtunk át,
// de előtte behívja az express-es auth middleware-t egy custom next fv-el.
// Az express-jwt middleware sikeres hitelesítés esetén berakja a requestbe
// (context) a usert (context.user), majd üres next-et hív: next(), míg
// hiba esetén átadja a hibát a next-nek: next(err), ez alapján resolve-áljuk,
// vagy reject-eljük a promise-ot.
const auth = (fn) => {
    //console.log("auth wrapper");
    // wrapper fv:
    return async (parent, params, context, info) => {
        // context az az express request
        //console.log(context.headers);

        // await-tel ha a promise rejected, akkor hibát dob (err throw)
        await new Promise((resolve, reject) => {
            authMw(context, null, (err) => {
                if (err) {
                    reject(err);
                }
                resolve();
            });
        });
        //console.log("ok");
        return fn(parent, params, context, info);
    };
};


// (parent, params, context, info) => {...}

module.exports = {
    Query: {
        hello: () => 'Hello world!',
        helloName: (_, { name }) => `Hello ${name}`,

        helloAuth: auth((_, params, context) => `Hello ${context.user.name}`),

        users: () => User.findAll(),
        user: (_, { id }) => User.findByPk(id),

        tickets: () => Ticket.findAll(),
        ticket: (_, { id }) => Ticket.findByPk(id),

        comments: () => Comment.findAll(),
        comment: (_, { id }) => Comment.findByPk(id),

        stats: async () => {
            const prio = await Ticket.findAll({
                attributes: [
                    'priority',
                    [sequelize.fn('COUNT', sequelize.col('ticket.id')), 'numTicket'],
                    [sequelize.fn('COUNT', sequelize.col('Comments.id')), 'numComment'],
                ],
                include: [
                    {
                        model: Comment,
                        as: 'Comments',
                        attributes: []
                    }
                ],
                group: ['priority'],
            });
            return prio.map(p => p.toJSON());
        }
    },

    User: {
        tickets: (user) => user.getTickets(),
        comments: (user) => user.getComments(),
    },

    Ticket: {
        users: (ticket) => ticket.getUsers(),
        comments: (ticket) => ticket.getComments(),
    },

    Comment: {
        user: (comment) => comment.getUser(),
        ticket: (comment) => comment.getTicket(),
    },
}
