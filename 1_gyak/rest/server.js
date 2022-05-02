const express = require('express');
require('express-async-errors');
const app = express();

app.use(express.json());

// app.use(function (req, res, next) {
//     req.alma = 1;
//     next();
// });

app.use('/tickets', require('./routers/tickets'));
app.use('/auth', require('./routers/auth'));

app.use((err, req, res, next) => {
    if(res.headerSent) {
        return next(err);
    }
    res.status(500).send({
        name: err.name,
        message: err.message,
        stack: err.stack.split(/$\s+/gm),
    })
});

app.listen(3000, () => {
    console.log(`Example app listening on port 3000`);
});
