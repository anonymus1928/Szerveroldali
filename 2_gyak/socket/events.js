// module.exports = io => {
//     io.on('connection', socket => {
//         console.log('Kliens csatlakozott.');
//         // console.log(socket);

//         socket.on('test', (data, ack) => {
//             console.log(data, ack);
//             socket.emit('test-client', 'szervertől a válasz');
//             ack({
//                 status: 'OK',
//             });
//         });
//     });
// };

module.exports = io => {
    game = [
        {
            startTime: 123456789,
            tips: [
                { client: 'socketid1', number: 12 },
                { client: 'socketid2', number: 1 },
                { client: 'socketid3', number: 1 },
                { client: 'socketid4', number: 2 },
                { client: 'socketid5', number: 12 },
            ],
        },
    ];

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

                if (game[game.length - 1].tips.find(tip => tip.client === socket.id)) {
                    throw new Error('Already tipped!');
                }

                game[game.length - 1].tips.push({
                    client: socket.id,
                    number,
                });
                ack({
                    status: 'OK',
                });
            } catch (error) {
                ack({
                    status: 'error',
                    message: error.message,
                });
            }
        });
    });

    gameOver = () => {
        const sorted = game[game.length - 1].tips.sort((a, b) => a.number - b.number);

        const winner = sorted.find(tip => sorted.filter(anotherTip => tip.number === anotherTip.number).length === 1);
        for (const { client, number } of game[game.length - 1].tips) {
            io.to(client).emit('game-over', {
                won: number === winner.number,
                tipped: number,
                winner
            });
        }
    }

    newGame = () => {
        if (game.length > 0) gameOver();
        game.push({
            statTime: Date.now(),
            tips: []
        });

        io.emit('new-game-started');
        const time = 10;
        console.log(`New game!`);
        setTimeout(newGame, time * 1000)
    }

    newGame();
};
