const express = require('express');
const models = require('../models');
const { User, Ticket, Comment } = models;

const router = express.Router();

router.get('/', async (req, res) => {
    const tickets = await Ticket.findAll();
    res.send(tickets);
});

router.get('/:id', async (req, res) => {
    const { id } = req.params;
    if (isNaN(parseInt(id))) {
        return res.sendStatus(400);
    }
    const ticket = await Ticket.findByPk(id);
    if (!ticket) {
        return res.sendStatus(404);
    }
    res.send(ticket);
});

module.exports = router;
