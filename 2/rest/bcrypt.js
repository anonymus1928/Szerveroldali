const bcrypt = require('bcrypt');

const password = 'password';

const hash1 = bcrypt.hashSync(password, bcrypt.genSaltSync(12));
const hash2 = bcrypt.hashSync(password, bcrypt.genSaltSync(12));

console.log(hash1);
console.log(hash2);

console.log(bcrypt.compareSync(password, hash1));
console.log(bcrypt.compareSync(password, hash2));
console.log(bcrypt.compareSync('asdf', hash2));
