const { readdir, readFile, writeFile } = require('fs');

// Sync
// console.log('START');
// console.log(readdirSync('./inputs'));
// console.log('END');

// Async callback
console.log('START');
readdir('./inputs', (err, files) => {
    if (err) throw err;
    const contents = [];
    files.forEach(file => {
        readFile(`./inputs/${file}`, (err, data) => {
            if (err) throw err;
            // console.log(data.toString());
            contents.push(data.toString());
            if (contents.length === files.length) {
                writeFile('output.txt', contents.join('\n'), err => {
                    if (err) throw err;
                    console.log('Vége');
                });
            }
        });
    });
});
console.log('END');
