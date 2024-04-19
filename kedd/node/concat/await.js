
const { readdir, readFile, writeFile } = require("fs");
const cowsay = require("cowsay");

// promisify(fn)
const promisify = fn => {
    return (...args) => {
        return new Promise((resolve, reject) => {
            const callbackFn = (err, res) => {
                if (err) reject(err);
                else resolve(res);
            };

            args.push(callbackFn);

            fn(...args);
        });
    };
};

const pReaddir = promisify(readdir);
const pReadFile = promisify(readFile);
const pWriteFile = promisify(writeFile);

(async () => {
    console.log('START');
    const files = await pReaddir('./inputs');
    // console.log(files);
    let contents = new Array();
    for(const file of files) {
        contents.push(await pReadFile(`./inputs/${file}`));
    }
    contents = contents.map(file => file.toString());
    await pWriteFile('./output.txt', contents.join('\n'));
    console.log('STOP');
})();



// console.log('START');
// pReaddir('./inputs')
//     .then(files => {
//         // console.log(files);
//         const promises = files.map(file => pReadFile(`./inputs/${file}`));
//         return Promise.all(promises);
//     })
//     .then(data => {
//         // console.log(data.map(d => d.toString()));
//         return data.map(d => d.toString()).join('\n');
//     })
//     .then(output => pWriteFile('./output.txt', output))
//     .then(() => console.log('VÉGE'))
//     .catch((err) => {
//         // console.error(err);
//         console.log(cowsay.say({
//             text : err.message,
//             e : "xX",
//             T : "U "
//         }));
//     });
// console.log('STOP');
