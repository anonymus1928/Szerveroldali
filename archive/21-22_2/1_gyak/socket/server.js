const { instrument } = require("@socket.io/admin-ui");
const { Server } = require("socket.io");
const { createServer } = require('http');
const events = require("./events");

const httpServer = createServer();

const io = new Server(httpServer, {
    cors: {
        origin: "*",
        methods: ['POST', 'GET'],
        credentials: true
    },
    allowEIO3: true,
});

instrument(io, {
    auth: false,
});

events(io)

io.listen(3000, () => {
    console.log('A szerver a 3000-es porton fut.');
});