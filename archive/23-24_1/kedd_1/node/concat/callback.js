const { readdir, readFile, writeFile } = require('fs');

console.log('START');
readdir('./inputs', (err, files) => {
    if (err) throw err;
    const contents = [];
    files.forEach(file => {
        readFile(`./inputs/${file}`, (err, data) => {
            if (err) throw err;
            // console.log(data.toString());
            contents.push(data.toString());
            writeFile('./output.txt', contents.join('\n'), err => {
                if (err) throw err;
            });
        });
    });
});
console.log('END');
