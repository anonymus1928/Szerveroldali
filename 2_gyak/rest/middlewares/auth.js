const jwt = require('express-jwt');

module.exports = jwt({ secret: "secret", algorithms: ['HS256'] });
