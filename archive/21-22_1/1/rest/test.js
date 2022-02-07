const models = require('./models');
const { User, Genre, Movie, Rating, sequelize } = models;
const { Op } = require('sequelize');
// const { sequelize } = require('./models');


;(async () => {
    // Összes film lekérése
    //console.log(await Movie.findAll());

    // Film lekérése id alapján
    // console.log(await Movie.findByPk(6));
    // console.log(await Movie.findOne({ where: { id: 6 } }));
    // console.log(await Movie.findByPk(9999999)); // null

    // Filmek száma
    // console.log(await Movie.count());

    // Azon filmek, ahol az év nagyobb, mint 1950
    // console.log(await Movie.findAll({
    //     where: {
    //         year: {
    //             [Op.gt]: 1950,
    //         }
    //     }
    // }));


    // Filmek lekérése a műfajokkal együtt
    // console.log(
    //     (await Movie.findByPk(1, {
    //         include: [{
    //             model: Genre,
    //             as: 'Genres',
    //             attributes: ['id', 'name'], // csak ezeket szeretném
    //             // attributes: { exclude:  ['description', 'createdAt', 'updatedAt']}, // csak ezeket nem szeretném
    //             through: {attributes: []},
    //         }],
    //     })).toJSON()
    // );

    // TODO
    // Film lekérdezése az értékelések átlagával
    // console.log(
    //     (
    //         await Movie.findByPk(1, {
    //             // Plusz mezők a meglévők mellé
    //             attributes: {
    //                 include: [
    //                     [sequelize.fn('AVG', sequelize.col('Ratings.rating')), 'avgRating']
    //                 ]
    //             },
    //             // Kapcsoló modellek
    //             include: [
    //                 {
    //                     model: Genre,
    //                     as: 'Genres',
    //                     attributes: ['id', 'name', 'description'],
    //                     through: {attributes: []},
    //                 },
    //                 {
    //                     model: Rating,
    //                     attributes: [],
    //                 }
    //             ]
    //         })
    //     ).toJSON()
    // );
    // Megadott jelszó tartozik-e felhasználóhoz
    // console.log((await User.findByPk(1)).comparePassword('password'));
    // console.log((await User.findByPk(1)).comparePassword('password2'));
    // Felhasználó JSON-ben való konvertálása (itt jelent gondot, hogy ha belekerül a jelszó...)
    // console.log((await User.findByPk(1)).toJSON());
})();
