'use strict';
const faker = require('faker');
const models = require('../models'); // index.js
const { User, Genre, Movie, Rating } = models;

module.exports = {
  up: async (queryInterface, Sequelize) => {
    // Felhasználók
    const usersCount = faker.datatype.number({min: 15, max: 30});
    const users = [];
    users.push(
        await User.create({
            name: 'Admin',
            email: 'q@q.hu',
            password: 'password',
            isAdmin: true
        })
    );
    for(let i = 1; i < usersCount; i++) {
        users.push(
            await User.create({
                name: faker.name.findName(),
                email: `q${i}@q.hu`,
                password: 'password',
                isAdmin: false
            })
        )
    }

    // Műfajok
    const genresCount = faker.datatype.number({min: 5, max: 10});
    const genres = [];
    for(let i = 1; i < genresCount; i++) {
        genres.push(
            await Genre.create({
                name: faker.lorem.word(),
                description: faker.lorem.sentence(),
            })
        )
    }

    // Filmek
    const moviesCount = faker.datatype.number({min: 15, max: 30});
    const movies = [];
    for(let i = 1; i < moviesCount; i++) {
        movies.push(
            await Movie.create({
                title: faker.lorem.words(faker.datatype.number({min: 1, max: 6})),
                director: faker.name.findName(),
                description: faker.lorem.sentence(),
                year: faker.datatype.number({min: 1870, max: new Date().getFullYear()}),
                length: faker.datatype.number({min: 60*60, max: 60*60*3}),
                imageUrl: faker.image.imageUrl(),
                ratingsEnabled: faker.datatype.boolean(),
            })
        )
    }

    // Relációk
    // 1. Genre - Movie
    // 2. Movie - Rating
    for (const movie of movies) {
        // Műfajok hozzárendelése
        await movie.setGenres(faker.random.arrayElements(genres));
        // Értékelések hozzárendelése, ha lehet értékelni
        if(movie.ratingsEnabled) {
            const randomUsers = faker.random.arrayElements(users);
            for (const user of randomUsers) {
                // movie.createRating(...); // alternatív mód
                await Rating.create({
                    rating: faker.datatype.number({ min: 1, max: 5 }),
                    comment: faker.datatype.boolean() ? faker.lorem.sentence() : '',
                    UserId: user.id,
                    MovieId: movie.id,
                });
            }
        }
    }
  },

  down: async (queryInterface, Sequelize) => {
    /**
     * Add commands to revert seed here.
     *
     * Example:
     * await queryInterface.bulkDelete('People', null, {});
     */
  }
};
