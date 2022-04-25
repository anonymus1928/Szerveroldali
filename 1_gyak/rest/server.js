const express = require('express');
const app = express();

app.use(express.json());

app.use('/tickets', require('./routers/tickets'));
app.use('/auth', require('./routers/auth'));

app.listen(3000, () => {
    console.log(`Example app listening on port 3000`);
});
