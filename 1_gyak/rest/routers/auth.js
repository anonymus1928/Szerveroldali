const express = require('express');
const jsonwebtoken = require('jsonwebtoken');
const models = require('../models');
const { User } = models;

const router = express.Router();

router.post('/register', async (req, res) => {
    const { email, name, password } = req.body;
    if ((!email, !name, !password)) {
        return res.status(400).send({ message: 'Valami hiányzik...' });
    }
    const user = await User.create({ email, name, password, isAdmin: false });
    const token = jsonwebtoken.sign(user.toJSON(), 'secret', { algorithm: 'HS256' });
    return res.status(201).send({ token });
});

router.get('/login', async (req, res) => {});

module.exports = router;
