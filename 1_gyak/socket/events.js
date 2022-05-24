// module.exports = (io) => io.on("connection", (socket) => {
//     console.log('Kliens csatlakozott');
//     // console.log(socket);

//     socket.on('test', (data,  ack) => {
//         console.log(data, ack);
//         ack({
//             status: 'OK'
//         });
//         socket.emit('test-client', 'válasz a szervertől');
//     });
// });

module.exports = io => {
    const games = [];

    io.on('connection', socket => {
        socket.on('tip', (data, ack) => {
            try {
                const { number } = data;
                if (!number) {
                    throw new Error('No number specified!');
                }
                if (isNaN(parseInt(number))) {
                    throw new Error('Not a number!');
                }
                if (games[games.length - 1].tips.find(tip => tip.client === socket.id)) {
                    throw new Error('Already tipped!');
                }
                games[games.length - 1].tips.push({
                    client: socket.id,
                    number,
                });
                ack({ status: 'OK' });
            } catch (error) {
                ack({ status: 'error', message: error.message });
            }
        });
    });

    const gameOver = () => {
        const sorted = games[games.length - 1].tips.sort((a, b) => a.number - b.number);
        const winner = sorted.find(
            tip => sorted.filter(otherTip => tip.number === otherTip.number).length === 1
        );
        console.log(sorted);
        console.log(winner);

        for (const { client, number } of games[games.length - 1].tips) {
            io.to(client).emit('game-over', {
                won: number === winner.number,
                tipped: number,
                winner,
            });
        }
    };

    const newGame = () => {
        if (games.length > 0) gameOver();

        games.push({
            startTime: Date.now(),
            tips: [],
        });

        io.emit('new-game-started');
        console.log('New game started!');
        setTimeout(newGame, 10000);
    };

    newGame();
};
