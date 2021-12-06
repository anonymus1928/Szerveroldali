const express = require('express');
require('express-async-errors');

// graphql playground
const expressPlayground = require('graphql-playground-middleware-express').default

const app = express();

app.use(express.json());

// routerek bindolása
app.use('/genres', require('./routers/genre'));
app.use('/movies', require('./routers/movie'));
app.use('/auth', require('./routers/auth'));

// GraphQL
app.use('/graphql', require('./graphql'));
app.use('/playground', expressPlayground({ endpoint: '/graphql' }));

// req -> middleware -> resp
//                   -> next
app.use((err, req, res, next) => {
    // Már el lett-e küldve valamilyen response.send()?
    if(res.headersSent) {
        return next(err);
    }
    // Általános hibakezelés
    res.status(500).send({
        name: err.name,
        message: err.message,
        stack: err.stack.split(/$\s+/gm),
    });
});

app.listen(3000, () => {
    console.log('A szerver fut.');
});
