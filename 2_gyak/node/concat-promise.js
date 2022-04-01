const { readdir, readFile, writeFile } = require('fs');
// const util = require('util');


const promisify2 = (fn) => {
    return (...args) => {
        return new Promise((resolve, reject) => {
            const callbackFn = (err, res) => {
                if(err) reject(err);
                else resolve(res);
            }

            args.push(callbackFn);
            fn(...args);
        });
    }
}


const pReaddir = promisify2(readdir);
const pReadFile = promisify2(readFile);
const pWriteFile = promisify2(writeFile);


pReaddir('./inputs').then(filenames => {
    console.log(filenames);
    let contents = [];
    const promises = filenames.map(filename => pReadFile(`./inputs/${filename}`));
    // console.log(promises);
    return Promise.all(promises);
})
.then(contents => {
    contents = contents.map(content => content.toString());
    console.log(contents);
    return contents.join('\n');
})
.then(output => pWriteFile('./output.txt', output))
.then(() => console.log('Vége'))
.catch(err => console.log(err));

// fs.readdir('./inputs', (err, filenames) => {
//     if(err) throw err;
//     console.log(filenames);
//     let contents = [];
//     filenames.forEach(filename => {
//         fs.readFile(`./inputs/${filename}`, (err, content) => {
//             if(err) throw err;
//             console.log(content.toString());
//             contents.push(content.toString());
//             if (contents.length === filenames.length) {
//                 fs.writeFile('./output.txt', contents.join('\n'), (err) => {
//                     if(err) throw err;
//                     console.log('Vége');
//                 });
//             }
//         });
//     });
// });

