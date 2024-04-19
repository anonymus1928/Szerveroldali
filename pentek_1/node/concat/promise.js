// Async callback
// console.log('START');
// readdir('./inputs', (err, files) => {
//     if(err) throw err;
//     // console.log(files);
//     const contents = [];
//     files.forEach(file => {
//         readFile(`./inputs/${file}`, (err, data) => {
//             if(err) throw err;
//             // console.log(data.toString());
//             contents.push(data.toString());
//             if(contents.length === files.length) {
//                 writeFile('./output.txt', contents.join('\n'), (err) => {
//                     if (err) throw err;
//                     console.log('Vége');
//                 });
//             }
//         });
//     });
// });
// console.log('STOP');

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

// promisify(fn);
