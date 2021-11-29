const express = require('express');
const models = require('../models');
const { User, Genre, Movie, Rating, sequelize } = models;

const router = express.Router();

// NAMESPACE: /genres

// Lekérdezések
router.get('/', async function (req, res) {
    const genres = await Genre.findAll();
    res.send(genres);
});

router.get('/:id', async function (req, res) {
    // console.log(req.params);
    const { id } = req.params;
    if(isNaN(parseInt(id))) {
        return res.sendStatus(400); // bad request
    }

    const genre = await Genre.findByPk(id);
    if(genre === null) {
        return res.sendStatus(404); // not found
    }
    res.send(genre);
});

// Létrehozás, módosítás, törlés
router.post('/', async function (req, res) {
    // console.log(req.body);
    const genre = await Genre.create(req.body);
    res.status(201).send(genre);
});

router.put('/:id', async function (req, res) {
    const { id } = req.params;
    if(isNaN(parseInt(id))) {
        return res.sendStatus(400); // bad request
    }

    const genre = await Genre.findByPk(id);
    if(genre === null) {
        return res.sendStatus(404); // not found
    }

    await genre.update(req.body);
    res.send(genre);
});

router.delete('/:id', async function (req, res) {
    const { id } = req.params;
    if(isNaN(parseInt(id))) {
        return res.sendStatus(400); // bad request
    }

    const genre = await Genre.findByPk(id);
    if(genre === null) {
        return res.sendStatus(404); // not found
    }

    await genre.destroy();
    res.sendStatus(200);
});

module.exports = router;
