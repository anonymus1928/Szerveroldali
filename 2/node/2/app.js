const faker = require('faker');

const randomName = faker.name.findName();
const randomEmail = faker.internet.email();
const randomCard = faker.helpers.createCard();

console.log(randomName, randomEmail, randomCard);