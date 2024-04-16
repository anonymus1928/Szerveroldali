// const { readdirSync } = require('fs');
const { readdir, readFile, writeFile } = require('fs');

// console.log('START');
// console.log(readdirSync('./inputs'));
// console.log('END');

// Async callback
console.log('START');
readdir('./inputs', (err, files) => {
    if (err) throw err;
    // console.log(files);
    const contents = [];
    files.forEach(file => {
        readFile(`./inputs/${file}`, (err, data) => {
            if(err) throw err;
            // console.log(data.toString());
            contents.push(data.toString());
        });
    });
    writeFile('output.txt', contents.join('\n'))
});
console.log('STOP');