const models = require('../models');
const { User, Ticket, Comment, sequelize } = models;
const authMW = require('../middlewares/auth')

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

/**
 * (parent, params, context, info) => {...}
 */

module.exports = {
    Query: {
        hello: () => 'Hello there',
        helloName: (_, { name }) => `Hello ${name}!`,

        helloAuth: auth((_, params, context) => `Hello ${context.user.name}`),

        users: () => User.findAll(),
        user: (_, { id }) => User.findByPk(id),

        tickets: () => Ticket.findAll(),
        ticket: (_, { id }) => Ticket.findByPk(id),

        comments: () => Comment.findAll(),
        comment: (_, { id }) => Comment.findByPk(id),

        stats: async () => {
            const stats = await Ticket.findAll({
                attributes: [
                    'priority',
                    [sequelize.fn('COUNT', sequelize.col('ticket.id')), 'ticketsNum'],
                    [sequelize.fn('COUNT', sequelize.col('Comments.id')), 'commentsNum'],
                ],
                include: [
                    {
                        model: Comment,
                        as: 'Comments',
                        attributes: [], // semmit sem szeretnék
                        // attributes: [...], // csak ezeket szeretném
                        // attributes: { exclude: [...] }, // ezeket kivéve mindent szeretnék
                    },
                ],
                group: ['ticket.priority'],
            });
            return stats.map(s => s.toJSON());
        }
    },

    User: {
        comments: (user) => user.getComments(),
        tickets: (user) => user.getTickets(),
    },

    Ticket: {
        comments: (ticket) => ticket.getComments(),
        users: (ticket) => ticket.getUsers(),
    },

    Comment: {
        user: (comment) => comment.getUser(),
        ticket: (comment) => comment.getTicket(),
    },
}