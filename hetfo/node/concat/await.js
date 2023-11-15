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
    try {
        const files = await pReaddir('./inputs');
        let contents = [];
        for (const file of files) {
            contents.push(await pReadFile(`./inputs/${file}`));
        }
        contents = contents.map(content => content.toString());
        await pWriteFile('./output.txt', contents.join('\n'));
    } catch {
        console.log(
            cowsay.say({
                text: 'ERROR',
                e: 'xX',
                T: 'U ',
            })
        );
    }
})();

// console.log('START');
// pReaddir('./inputs')
//     .then(files => {
//         // console.log(files);
//         const promises = files.map(file => pReadFile(`./inputs/${file}`));
//         return Promise.all(promises);
//     })
//     .then(contents => {
//         contents = contents.map(data => data.toString());
//         // console.log(contents);
//         return contents.join('\n');
//     })
//     .then(output => pWriteFile('./output.txt', output))
//     .then(() => console.log('vége'))
//     .catch(err =>
//         console.log(
//             cowsay.say({
//                 text: 'ERROR',
//                 e: 'xX',
//                 T: 'U ',
//             })
//         )
//     );
// console.log('END');
