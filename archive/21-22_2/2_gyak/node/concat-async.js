const { readdir, readFile, writeFile } = require('fs');
const { promisify } = require('util');

const pReaddir = promisify(readdir);
const pReadFile = promisify(readFile);
const pWriteFile = promisify(writeFile);

// pReaddir('./inputs').then(filenames => {
//     console.log(filenames);
//     let contents = [];
//     const promises = filenames.map(filename => pReadFile(`./inputs/${filename}`));
//     // console.log(promises);
//     return Promise.all(promises);
// })
// .then(contents => {
//     contents = contents.map(content => content.toString());
//     console.log(contents);
//     return contents.join('\n');
// })
// .then(output => pWriteFile('./output.txt', output))
// .then(() => console.log('Vége'))
// .catch(err => console.log(err));

(async () => {
    const filenames = await pReaddir('./inputs');
    let contents = [];
    for (const filename of filenames) {
        contents.push(await pReadFile(`./inputs/${filename}`));
    }
    contents = contents.map(content => content.toString());
    await pWriteFile('./output.txt', contents.join('\n'));
})();
