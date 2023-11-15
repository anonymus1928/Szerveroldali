const { readdir, readFile, writeFile } = require('fs');
const cowsay = require('cowsay');

// console.log('START');
// fs.readdir('./inputs', (err, files) => {
//     if (err) throw err;
//     // console.log(files);
//     const contents = [];
//     files.forEach((file) => {
//         fs.readFile(`./inputs/${file}`, (err, data) => {
//             if (err) throw err;
//             // console.log(data.toString());
//             contents.push(data.toString());
//             if(contents.length === files.length) {
//                 fs.writeFile('./output.txt', contents.join('\n'), (err) => {
//                     if (err) throw err;
//                 });
//             }
//         });
//     });
// });
// console.log('END');

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
        // console.log(files);
        const promises = files.map(file => pReadFile(`./inputs/${file}`));
        return Promise.all(promises);
    })
    .then(contents => {
        contents = contents.map(data => data.toString());
        // console.log(contents);
        return contents.join('\n');
    })
    .then(output => pWriteFile('./output.txt', output))
    .then(() => console.log('vége'))
    .catch(err =>
        console.log(
            cowsay.say({
                text: 'ERROR',
                e: 'xX',
                T: 'U ',
            })
        )
    );
console.log('END');
