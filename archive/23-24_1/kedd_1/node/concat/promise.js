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

console.log('START');
pReaddir('./inputs')
    .then(files => {
        const promises = files.map(file => pReadFile(`./inputs/${file}`));
        return Promise.all(promises);
    })
    .then(datas => {
        contents = datas.map(data => data.toString());
        return contents.join('\n');
    })
    .then(output => {
        pWriteFile('./output.txt', output);
    })
    .then(() => console.log('VÉGE'))
    .catch(err => {throw err});
console.log('END');

// console.log('START');
// readdir('./inputs', (err, files) => {
//     if (err) throw err;
//     const contents = [];
//     files.forEach(file => {
//         readFile(`./inputs/${file}`, (err, data) => {
//             if (err) throw err;
//             // console.log(data.toString());
//             contents.push(data.toString());
//             writeFile('./output.txt', contents.join('\n'), err => {
//                 if (err) throw err;
//             });
//         });
//     });
// });
// console.log('END');
