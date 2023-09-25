const express = require('express');
const auth = require('../middlewares/auth');
const models = require('../models');
const { User, Ticket, Comment, sequelize } = models;

const router = express.Router();

router.get('/', auth, async (req, res) => {
    const tickets = await Ticket.findAll();
    res.send(tickets);
});

router.get('/stats', auth, async (req, res) => {
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
    res.send(stats);
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
    if (!title || !priority || !text) {
        return res.status(400).send({ message: "Valami hiányzik..." });
    }
    if (isNaN(parseInt(priority)) || ![0,1,2,3].includes(priority)) {
        return res.status(400).send({message: "Priority 0 és 4 közti egész szám lehet."});
    }

    const ticket = await Ticket.create({
        title,
        priority,
    });
    await ticket.addUser(req.user.id);
    await ticket.createComment({
        text,
        UserId: req.user.id,
    });
    const sendTicket = await Ticket.findByPk(ticket.id, {
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
    res.send(sendTicket);
});

router.put('/:id', auth, async (req, res) => {
    const { id } = req.params;
    if (isNaN(parseInt(id))) {
        return res.sendStatus(400);
    }
    const { title, priority } = req.body;
    if (!title || !priority) {
        return res.status(400).send({ message: "Valami hiányzik..." });
    }
    if (isNaN(parseInt(priority)) || ![0,1,2,3].includes(priority)) {
        return res.status(400).send({message: "Priority 0 és 4 közti egész szám lehet."});
    }
    const ticket = await Ticket.findByPk(id);
    if (!ticket) {
        return res.sendStatus(404);
    }
    Ticket.update({
        title,
        priority
    }, {
        where: {
            id
        }
    });
    ticket.update({
        title, priority
    });
    res.send(ticket);
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
    res.send({ message: 'OK' });
});

module.exports = router;
