const { makeExecutableSchema } = require('@graphql-tools/schema');
const graphQLScalars = require('graphql-scalars');
const { readFileSync } = require('fs');
const { join } = require('path');
const mercurius = require('mercurius');

const schema = makeExecutableSchema({
    typeDefs: [graphQLScalars.typeDefs, readFileSync(join(__dirname, 'typedefs.gql')).toString()],
    resolvers: [graphQLScalars.resolvers, require(join(__dirname, 'resolvers'))],
});

const context = request => {
    return {
        request,
    };
};

module.exports = fastify => {
    fastify.register(mercurius, {
        graphiql: true,
        schema,
        context,
    });
};
