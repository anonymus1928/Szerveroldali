const { readdir, readFile, writeFile } = require('fs');
const cowsay = require('cowsay');

const promisify = fn => {
    return (...args) => {
        return new Promise((resolve, reject) => {
            const callbackFn = (err, res) => {
                if (err) reject(err);
                else resolve(res);
            };

            args.push(callbackFn);

            fn(...args);
        });
    };
};

const pReaddir = promisify(readdir);
const pReadFile = promisify(readFile);
const pWriteFile = promisify(writeFile);

(async () => {
    const files = await pReaddir('./inputs');
    let contents = [];
    for(const file of files) {
        contents.push(await pReadFile(`./inputs/${file}`));
    }
    contents = contents.map(file => file.toString());
    await pWriteFile('./output.txt', contents.join('\n'));
})();

// pReaddir('./inputs')
//     .then(files => {
//         console.log(files);
//         const promises = files.map(file => pReadFile(`./inputs/${file}`));
//         return Promise.all(promises);
//     })
//     .then(contents => {
//         contents = contents.map(file => file.toString());
//         console.log(contents);
//         return contents.join('\n');
//     })
//     .then(output => pWriteFile('./output.txt', output))
//     .then(() => console.log('Vége'))
//     .catch(err =>
//         console.log(
//             cowsay.say({
//                 text: 'Ajjajj',
//                 e: 'xX',
//                 T: 'U ',
//             })
//         )
//     );
