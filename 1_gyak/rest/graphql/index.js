const { graphqlHTTP } = require('express-graphql');
const { buildSchema } = require('graphql');
const { readFileSync } = require('fs');
const { makeExecutableSchema } = require('graphql-tools');
const { join } = require('path');

const resolvers = require('./resolvers');
const typeDefs = readFileSync(join(__dirname, './typedefs.graphql')).toString();

const schema = makeExecutableSchema({
    typeDefs: [typeDefs],
    resolvers: [resolvers],
});

module.exports = graphqlHTTP({
    schema,
    graphiql: {
        headerEditorEnabled: true
    },
});
