const models = require('./models');
const { User, Genre, Movie, Rating, sequelize } = models;
const { Op } = require('sequelize');

// Self-invoke function
// Önkioldó függvény
;(async () => {
    // Összes film lekérdezése
    // console.log(await Movie.findAll());

    // Egy adott film lekérdezése, id alapján
    // console.log(await Movie.findByPk(21));
    // console.log(await Movie.findOne({ where: { id: 21 } }));
    // console.log(await Movie.findByPk(9999)); // null

    // Azon filmek, amik 1950 után készültek
    // console.log(await Movie.findAll({
    //     where: {
    //         year: {
    //             [Op.gt]: 1950,
    //         }
    //     }
    // }));

    // Film lekérése műfajokkal
    // console.log(
    //     (
    //         await Movie.findByPk(1, {
    //             include: [{
    //                 model: Genre,
    //                 as: 'Genres',
    //                 attributes: ['id', 'name', 'description'], // csak ezeket szeretném megkapni
    //                 // attributes: { exclude: ['createdAt', 'updatedAt'] } // csak ezeket nem szeretném megkapni
    //                 through: { attributes: [] }
    //             }],
    //         })
    //     ).toJSON()
    // );

    // Film lekérése az értékelések átlagával
    console.log(
        (
            await Movie.findByPk(1, {
                // Plusz mező a meglévők mellé
                attributes: {
                    include: [
                        [sequelize.fn('AVG', sequelize.col('Ratings.rating')), 'avgRating'],
                    ],
                },
                include: [
                    {
                        model: Genre,
                        as: 'Genres',
                        attributes: ['id', 'name', 'description'], // csak ezeket szeretném megkapni
                        // attributes: { exclude: ['createdAt', 'updatedAt'] } // csak ezeket nem szeretném megkapni
                        through: { attributes: [] }
                    },
                    {
                        model: Rating,
                        attributes: []
                    }
                ],
            })
        ).toJSON()
    );


    // Jelszó ellenőrzése
    // console.log((await User.findByPk(1)).comparePassword('password'));  // true
    // console.log((await User.findByPk(1)).comparePassword('password2')); // false

    // Felhasználó lekérdezése
    // gond: user jelszava benne van a lekérdezésben -> ki kell szedni
    // console.log((await User.findByPk(1)).toJSON());
})();
