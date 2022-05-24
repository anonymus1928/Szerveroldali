const { instrument } = require('@socket.io/admin-ui');
const { createServer } = require('http');
const { Server } = require("socket.io");
const events = require('./events');

const httpServer = createServer();

const io = new Server(httpServer, {
    cors: {
        origin: '*',
        methods: ['GET', 'POST'],
        credentials: true,
    },
    allowEIO3: true,
});

instrument(io, {
    auth: false,
});

events(io);

httpServer.listen(3000, () => {
    console.log('A szerver a 3000-es porton fut!');
});
