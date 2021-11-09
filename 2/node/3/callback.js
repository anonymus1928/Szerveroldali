const fs = require('fs');

fs.readdir('./inputs', (err, filenames) => {
    // console.log(filenames);
    let datas = [];
    filenames.forEach((filename) => {
        fs.readFile('./inputs/' + filename, 'utf-8', (err, data) => {
            datas.push(data);
            // console.log(data);
            if (datas.length === filenames.length) {
                const outData = datas.join('\n');
                fs.writeFile('./output.txt', outData, (err) => {
                    console.log('Program vége.');
                });
            }
        });
    });
});
