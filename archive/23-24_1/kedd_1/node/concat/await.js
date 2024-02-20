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
const pReadFile = promisify(readFile);
const pWriteFile = promisify(writeFile);

(async () => {
    const files = await pReaddir('./inputs');
    let contents = [];
    for(const file of files) {
        contents.push(await pReadFile(`./inputs/${file}`));
    }
    contents = contents.map((content) => content.toString());
    await pWriteFile('./output.txt', contents.join('\n'));
})();

// console.log('START');
// pReaddir('./inputs')
//     .then(files => {
//         const promises = files.map(file => pReadFile(`./inputs/${file}`));
//         return Promise.all(promises);
//     })
//     .then(datas => {
//         contents = datas.map(data => data.toString());
//         return contents.join('\n');
//     })
//     .then(output => {
//         pWriteFile('./output.txt', output);
//     })
//     .then(() => console.log('VÉGE'))
//     .catch(err => {throw err});
// console.log('END');
