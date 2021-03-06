const models = require('../models');
const { User, Genre, Movie, Rating, sequelize } = models;

// Auth middleware
const authMW = require('../middlewares/auth');

const auth = (fn) => {
    // wrapper függvény
    return async (parent, params, context, info) => {
        // authMW(req, res, next)
        await new Promise((resolve, reject) => {
            authMW(context, null, (error) => {
                if(error) {
                    reject(error);
                }
                resolve();
            });
        });

        return fn(parent, params, context, info);
    }
}


module.exports = {
    Query: {
        hello: () => 'Hello there!',
        // (parent, params, context, info)
        helloName: (_, { name }) => `Hello ${name}!`,

        // Auth
        helloAuth: auth((_, params, context) => `Hello ${context.user.name}!`),

        genres: () => Genre.findAll(),
        genre: (_, { id }) => Genre.findByPk(id),

        movies: () => Movie.findAll(),
        movie: (_, { id }) => Movie.findByPk(id),

        users: () => User.findAll(),
        user: (_, { id }) => User.findByPk(id),

        top: async (_, { limit }) => {
            return (
                await Movie.findAll({
                    attributes: {
                        include: [
                            [sequelize.fn('AVG', sequelize.col('rating')), 'averageRating'],
                        ],
                    },
                    include: [
                        {
                            model: Rating,
                            attributes: [],
                        }
                    ],
                    group: ['movie.id'],
                    order: sequelize.literal('averageRating DESC'),
                })
            ).slice(0, limit);
        }
    },
    Mutation: {
        rate: auth(async (_, { movieId, rating, comment }, context) => {
            const movie = await Movie.findByPk(movieId);
            if(movie === null) throw new Error('A megadott film nem létezik!');
            if(!movie.ratingsEnabled) throw new Error('A filmet nem lehet értékelni!');

            // A felhasználó értékelte-e már a filmet?
            let ratingDB = await Rating.findOne({ where: { MovieId: movie.id, UserId: context.user.id } });
            if(ratingDB === null) {
                // Még nem értékelte --> új értékelés
                ratingDB = await Rating.create({
                    rating,
                    comment,
                    MovieId: movieId,
                    UserId: context.user.id,
                });
                return {
                    newRating: true,
                    rating: ratingDB,
                };
            } else {
                // Már értékelte --> értékelés módosítása
                ratingDB = await ratingDB.update({
                    rating,
                    comment,
                });
                return {
                    newRating: false,
                    rating: ratingDB,
                };
            }
        }),
        deleteRate: auth(async (_, { movieId }, context) => {
            const movie = await Movie.findByPk(movieId);
            if(movie === null) throw new Error('A megadott film nem létezik!');
            if(!movie.ratingsEnabled) throw new Error('A filmet nem lehet értékelni!');

            // A felhasználó értékelte-e már a filmet?
            let ratingDB = await Rating.findOne({ where: { MovieId: movie.id, UserId: context.user.id } });
            if(!ratingDB) return false;
            await ratingDB.destroy();
            return true;
        }),
    },
    Genre: {
        movies: (genre) => genre.getMovies(),
    },
    Movie: {
        genres: (movie) => movie.getGenres(),
        ratings: (movie) => movie.getRatings(),

        averageRating: async (movie) => {
            const result = await movie.getRatings({
                attributes: [[sequelize.fn('AVG', sequelize.col('rating')), 'averageRating']],
                raw: true,
            });
            // console.log(result);
            return result[0].averageRating;
        }
    },
    Rating: {
        movie: (rating) => rating.getMovie(),
        user: (rating) => rating.getUser(),
    }
}