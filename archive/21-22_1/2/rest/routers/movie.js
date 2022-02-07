const express = require('express');
const models = require('../models');
const { User, Genre, Movie, Rating, sequelize } = models;

const auth = require('../middlewares/auth');

const router = express.Router();

// NAMESPACE: /movies

// Lekérdezés
router.get('/', async function (req, res) {
    const movies = await Movie.findAll({
        attributes: {
            include: [
                [sequelize.fn('AVG', sequelize.col('Ratings.rating')), 'avgRating'],
                [sequelize.fn('TIME', sequelize.col('length'), 'unixepoch'), 'lengthFormatted'], // ÓÓ:PP:MP
            ],
        },
        include: [
            {
                model: Genre,
                as: 'Genres',
                attributes: ['id', 'name', 'description'],
                through: {attributes: []},
            },
            {
                model: Rating,
                attributes: [],
            }
        ],
        group: ['movie.id', 'Genres.id'],
        order: sequelize.literal('avgRating DESC'),
    });
    return res.send(movies);
});

// Rating létrehozása és módosítása
router.post('/:id/rating', auth, async function (req, res) {
    const { id } = req.params;
    if(isNaN(parseInt(id))) {
        return res.status(400).send({ message: 'A megadott ID nem szám!' }); // bad request
    }

    const movie = await Movie.findByPk(id);
    if(movie === null) {
        return res.status(404).send({ message: 'A megadott film nem létezik!' }); // not found
    }

    // A felhasználó értékelte-e már a filmet?
    let rating = await Rating.findOne({ where: { MovieId: movie.id, UserId: req.user.id } });
    if(rating === null) {
        // Még nem értékelte --> új értékelés
        rating = await Rating.create({ ...req.body, MovieId: id, UserId: req.user.id });
        return res.status(201).send({ message: 'Új értékelést hoztál létre ehhez a filmhez!', rating });
    } else {
        // Már értékelte --> értékelés módosítása
        rating = await rating.update(req.body);
        return res.send({ message: 'Módosítottad az előző értékelésedet!', rating });
    }
});


// Rating törlése
router.delete('/:id/rating', auth, async function (req, res) {
    const { id } = req.params;
    if(isNaN(parseInt(id))) {
        return res.status(400).send({ message: 'A megadott ID nem szám!' }); // bad request
    }

    const movie = await Movie.findByPk(id);
    if(movie === null) {
        return res.status(404).send({ message: 'A megadott film nem létezik!' }); // not found
    }

    // A felhasználó értékelte-e már a filmet?
    let rating = await Rating.findOne({ where: { MovieId: movie.id, UserId: req.user.id } });
    if(rating !== null) {
        // Már értékelte
        await rating.destroy();
        return res.send({ message: 'Sikeresen törölted az értékelésedet a megadott filmről!' });
    } else {
        return res.status(404).send({ message: 'Még nem értékelted az adott filmet!' });
    }
});


module.exports = router;