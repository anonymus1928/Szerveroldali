const express = require('express');
const jsonwebtoken = require('jsonwebtoken');
const models = require('../models');
const { User, Genre, Movie, Rating, sequelize } = models;
const auth = require('../middlewares/auth');

const router = express.Router();

router.post('/login', async function (req, res) {
    const { email, password } = req.body;
    if(!email, !password) {
        return res.status(400).send({ message: 'Nem adtál meg email vagy jelszót!' });
    }

    const user = await User.findOne({ where: { email } });
    if(!user) {
        return res.status(404).send({ message: 'A megadott email címmel nincs regisztrálva felhasználó!' });
    }

    if(user.comparePassword(password)) {
        const token = jsonwebtoken.sign(user.toJSON(), 'secret', { algorithm: 'HS256' });
        return res.send({ token });
    }
    return res.status(401).send({ message: 'A megadott jelszó helytelen!' });
});

router.get('/who', auth, async function (req, res) {
    res.send(req.user);
});

router.post('/register', async function (req, res) {
    const { email, name, password } = req.body;
    if(!email || !name || !password) {
        return res.status(400).send({ message: 'Nem adtál meg email, nevet vagy jelszót!' });
    }

    const user = await User.create({ email, name, password, isAdmin: false });

    const token = jsonwebtoken.sign(user.toJSON(), 'secret', { algorithm: 'HS256' });
    return res.status(201).send({ token });
});

module.exports = router;
