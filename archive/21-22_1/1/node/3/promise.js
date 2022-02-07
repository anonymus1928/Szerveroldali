const fs = require('fs');
const { promisify } = require('util');

const pReadDir = promisify(fs.readdir);
const pReadFile = promisify(fs.readFile);
const pWriteFile = promisify(fs.writeFile);

pReadDir('./inputs')
    .then(filenames => {
        const promises = filenames.map(filename => pReadFile('./inputs/' + filename, 'utf-8'))
        // console.log(promises);
        return Promise.all(promises);
    })
    .then(datas => datas.join('\n'))
    .then(outData => pWriteFile('./output-promise.txt', outData))
    .then(() => console.log('Program vége'))
    .catch(err => console.error(err));


// fs.readdir('./inputs', (err, filenames) => {
//     //console.log(filenames);
//     let datas = [];
//     filenames.forEach(filename => {
//         fs.readFile('./inputs/' + filename, 'utf-8', (err, data) => {
//             datas.push(data);
//             console.log(data);
//             if (datas.length === filenames.length) {
//                 const outData = datas.join('\n');
//                 fs.writeFile('./output.txt', outData, (err) => {
//                     console.log('Program vége');
//                 });
//             }
//         });
//     });
//     // console.log('XXXXXX', datas);
// });
