const fs = require('fs');
const { promisify } = require('util');

const pReadDir = promisify(fs.readdir);
const pReadFile = promisify(fs.readFile);
const pWriteFile = promisify(fs.writeFile);

async function task() {
    const filenames = await pReadDir('./inputs');
    // console.log(filenames);
    const promises = filenames.map(filename => pReadFile('./inputs/' + filename, 'utf-8'));
    const datas = await Promise.all(promises);
    // console.log(datas);
    const outData = datas.join('\n');
    await pWriteFile('./output-async.txt', outData);
    console.log('Program vége.');
}

task();

// pReadDir('./inputs')
//     .then((filenames) => {
//         // console.log(filenames);
//         const promises = filenames.map(filename => pReadFile('./inputs/' + filename, 'utf-8'));
//         return Promise.all(promises);
//     })
//     .then(datas => datas.join('\n'))
//     .then(outData => pWriteFile('./output-promise.txt', outData))
//     .then(() => console.log('Program vége.'))
//     .catch(err => console.error(err));