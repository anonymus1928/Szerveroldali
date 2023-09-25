const express = require('express');
const auth = require('../middlewares/auth');
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

router.post('/login', async (req, res) => {
    const { email, password } = req.body;
    if ((!email, !password)) {
        return res.status(400).send({ message: 'Valami hiányzik...' });
    }
    const user = await User.findOne({ where: { email } });
    if(!user) {
        return res.status(404).send({ message: 'Nincs ilyen felhasználó.' });
    }
    if(user.comparePassword(password)) {
        const token = jsonwebtoken.sign(user.toJSON(), 'secret', { algorithm: 'HS256' });
        return res.send({ token });
    }
    return res.status(401).send({ message: 'Hibás jelszó.' });
});

router.get('/who', auth, async (req, res) => {
    return res.send({ user: req.user });
});

module.exports = router;
