const { readdir, readFile, writeFile } = require('fs');

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
const preadFile = promisify(readFile);
const pWriteFile = promisify(writeFile);

// console.log('START');
// pReaddir('./inputs')
//     .then(files => {
//         // console.log(files);
//         const promises = files.map(file => preadFile(`./inputs/${file}`));
//         // console.log(promises);
//         return Promise.all(promises);
//     })
//     .then(contents => {
//         // console.log(contents);
//         contents = contents.map(content => content.toString());
//         // console.log(contents);
//         return contents.join('\n');
//     })
//     .then(output => pWriteFile('./output.txt', output))
//     .then(() => console.log('Vége'))
//     .catch(err => console.error(cowsay.say({
//         text : err.message,
//         e : "xX",
//         T : "U "
//     })));
// console.log('STOP');

console.log('START');
(async () => {
    const files = await pReaddir('./inputs');
    // console.log(files);
    let contents = [];
    for(const file of files) {
        contents.push(await preadFile(`./inputs/${file}`));
    }
    contents = contents.map(content => content.toString());
    // console.log(contents);
    await pWriteFile('./output.txt', contents.join('\n'));
})();
console.log('STOP');
