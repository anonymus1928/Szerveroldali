const fs = require('fs');

// const util = require('util');

const promisify2 = (fn) => {
    return (...args) => {
        return new Promise((resolve, reject) => {
            const callbackFn = (err, res) => {
                if (err) reject(err);
                else resolve(res);
            }

            args.push(callbackFn);

            fn(...args);
        });
    }
}


const pReaddir = promisify2(fs.readdir);
const pReadFile = promisify2(fs.readFile);
const pWriteFile = promisify2(fs.writeFile);


pReaddir('./inputs')
    .then(filenames => {
        console.log(filenames);
        const promises = filenames.map(filename => pReadFile(`./inputs/${filename}`));
        return Promise.all(promises);
    })
    .then(contents => {
        contents = contents.map(content => content.toString());
        console.log(contents);
        return contents.join('\n');
    })
    .then(output => pWriteFile('./output.txt', output))
    .then(() => console.log('Vége'))
    .catch((err) => {throw err});



// fs.readdir('./inputs', (err, filenames) => {
//     if (err) throw err;
//     console.log(filenames);
//     const contents = [];
//     filenames.forEach(filename => {
//         fs.readFile(`./inputs/${filename}`, (err, content) => {
//             if (err) throw err;
//             console.log(content.toString());
//             contents.push(content.toString());
//             if(filenames.length === contents.length) {
//                 fs.writeFile('./output.txt', contents.join('\n'), (err) => {
//                     if (err) throw err;
//                     console.log('Vége');
//                 });
//             }
//         });
//     });
// });


