const express = require('express');
const auth = require('../middlewares/auth');
const models = require('../models');
const { Ticket, Comment, User, sequelize } = models;

const router = express.Router();

router.get('/', auth, async (req, res) => {
    const tickets = await Ticket.findAll();
    res.send(tickets);
});

router.get('/stats', auth, async (req, res) => {
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
    res.send({ stats: prio });
});

router.get('/:id', auth, async (req, res) => {
    const { id } = req.params;
    if (isNaN(parseInt(id))) {
        return res.sendStatus(400);
    }
    const ticket = await Ticket.findByPk(id, {
        include: [
            {
                model: Comment,
                attributes: [ 'id', 'text' ],
                include: [
                    {
                        model: User,
                        attributes: { exclude: [ 'createdAt', 'updatedAt', 'password' ] }
                    }
                ]
            },
            {
                model: User,
                as: "Users",
                attributes: { exclude: [ 'createdAt', 'updatedAt', 'password' ] },
                through: { attributes: [] } // ez azért kell, hogy ne szemetelje bele a pivot táblát
            }
        ]
    });
    if (!ticket) {
        return res.sendStatus(404);
    }
    res.send(ticket);
});

router.post('/', auth, async (req, res) => {
    const { title, priority, text } = req.body;
    if ((!title, !priority, !text)) {
        return res.status(400).send({ message: 'Valami hiányzik...' });
    }
    if (isNaN(parseInt(priority)) || ![0,1,2,3].includes(parseInt(priority))) {
        return res.status(400).send({ message: 'A priority 0 és 4 közötti egész szám lehet.' });
    }
    const ticket = await Ticket.create({ title, priority, done: false });
    await ticket.addUser(req.user.id);
    await ticket.createComment({ text, UserId: req.user.id });
    const resTicket = await Ticket.findByPk(ticket.id, {
        include: [
            {
                model: Comment,
                attributes: [ 'id', 'text' ],
                include: [
                    {
                        model: User,
                        attributes: { exclude: [ 'createdAt', 'updatedAt', 'password' ] }
                    }
                ]
            },
            {
                model: User,
                as: "Users",
                attributes: { exclude: [ 'createdAt', 'updatedAt', 'password' ] },
                through: { attributes: [] }
            }
        ]
    });
    res.status(201).send({ ticket: resTicket });
});

router.put('/:id', auth, async (req, res) => {
    const { id } = req.params;
    if (isNaN(parseInt(id))) {
        return res.sendStatus(400);
    }
    const { title, priority } = req.body;
    if ((!title, !priority)) {
        return res.status(400).send({ message: 'Valami hiányzik...' });
    }
    if (isNaN(parseInt(priority)) || ![0,1,2,3].includes(parseInt(priority))) {
        return res.status(400).send({ message: 'A priority 0 és 4 közötti egész szám lehet.' });
    }
    const ticket = await Ticket.update({ title, priority }, {
        where: {
            id
        }
    });
    return res.send({ ticket });
});

router.delete('/:id', auth, async (req, res) => {
    const { id } = req.params;
    if (isNaN(parseInt(id))) {
        return res.sendStatus(400);
    }
    await Ticket.destroy({
        where: {
            id
        }
    });
    return res.send({ status: 'OK' });
});

module.exports = router;
