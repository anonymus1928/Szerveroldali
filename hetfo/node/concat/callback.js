const fs = require('fs');

console.log('START');
fs.readdir('./inputs', (err, files) => {
    if (err) throw err;
    // console.log(files);
    const contents = [];
    files.forEach((file) => {
        fs.readFile(`./inputs/${file}`, (err, data) => {
            if (err) throw err;
            // console.log(data.toString());
            contents.push(data.toString());
            if(contents.length === files.length) {
                fs.writeFile('./output.txt', contents.join('\n'), (err) => {
                    if (err) throw err;
                });
            }
        });
    });
});
console.log('END');
