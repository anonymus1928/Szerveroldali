// const { readdirSync } = require('fs');
const { readdir, readFile, writeFile } = require('fs');

// Sync
// console.log('START');
// console.log(readdirSync('./inputs'));
// console.log('STOP');


// Async callback
console.log('START');
readdir('./inputs', (err, files) => {
    if(err) throw err;
    // console.log(files);
    const contents = [];
    files.forEach(file => {
        readFile(`./inputs/${file}`, (err, data) => {
            if(err) throw err;
            // console.log(data.toString());
            contents.push(data.toString());
            if(contents.length === files.length) {
                writeFile('./output.txt', contents.join('\n'), (err) => {
                    if (err) throw err;
                    console.log('Vége');
                });
            }
        });
    });
});
console.log('STOP');
