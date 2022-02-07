const { graphqlHTTP } = require('express-graphql');
const { makeExecutableSchema } = require('@graphql-tools/schema');
const { readFileSync } = require('fs');
const { join } = require('path');

// GraphQL Scalars
const { typeDefs: scalarsTypeDefs, resolvers: scalarsResolvers } = require('graphql-scalars')


const ourTypeDefs = readFileSync(join(__dirname, './typedefs.graphql')).toString();
const ourResolvers = require('./resolvers');

const schema = makeExecutableSchema({
    typeDefs: [scalarsTypeDefs, ourTypeDefs],
    resolvers: [scalarsResolvers, ourResolvers],
});


module.exports = graphqlHTTP({
    schema, // === schema: schema
    graphiql: {
        headerEditorEnabled: true, // HTTP fejlécelemek küldése
    }
});