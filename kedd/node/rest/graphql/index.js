const { readFileSync } = require('fs');
const { join } = require('path');
const graphqlScalars = require('graphql-scalars');
const { makeExecutableSchema } = require('@graphql-tools/schema');
const mercurius = require('mercurius');

const schema = makeExecutableSchema({
    typeDefs: [graphqlScalars.typeDefs, readFileSync(join(__dirname, 'typedefs.gql')).toString()],
    resolvers: [graphqlScalars.resolvers, require(join(__dirname, 'resolvers'))],
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
