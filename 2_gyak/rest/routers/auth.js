const express = require('express');
const jsonwebtoken = require('jsonwebtoken');
const models = require('../models');
const { User, Ticket, Comment } = models;

const router = express.Router();

router.get('/login', async (req, res) => {
    const tickets = await Ticket.findAll();
    res.send(tickets);
});

router.get('/who', async (req, res) => {

});

router.post('/register', async (req, res) => {
    const { email, name, password } = req.body;
    if (!email || !name || !password) {
        return res.status(400).send({ message: "Valami hiányzik..." });
    }
    const user = await User.create({ email, name, password, isAdmin: false });
    // bejelentkezés
    const token = jsonwebtoken.sign(user.toJSON(), "secret", { algorithm: 'HS256' });
    return res.status(201).send({ token });
});

module.exports = router;