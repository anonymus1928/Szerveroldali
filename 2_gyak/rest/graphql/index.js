const { graphqlHTTP } = require('express-graphql');
const { makeExecutableSchema } = require('graphql-tools');
const { buildSchema } = require('graphql');
const { join } = require('path');
const { readFileSync } = require('fs');
const { typeDefs: scalarsTypeDefs, resolvers: scalarsResolvers } = require('graphql-scalars');

const resolvers = require('./resolvers');
const typeDefs  = readFileSync(join(__dirname, "./typedefs.graphql")).toString();

const schema = makeExecutableSchema({
    typeDefs: [scalarsTypeDefs, typeDefs],
    resolvers: [scalarsResolvers, resolvers]
});


module.exports = graphqlHTTP({
    schema,
    graphiql: {
        headerEditorEnabled: true,
    }
});
