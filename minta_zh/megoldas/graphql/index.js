const { join } = require("path");
const mercurius = require("mercurius");
const graphQLScalars = require("graphql-scalars");
const { readFileSync } = require("fs");
const { makeExecutableSchema } = require("@graphql-tools/schema");

// A graphq-scalars és a saját graphql schema összefűzése
const schema = makeExecutableSchema({
    typeDefs: [graphQLScalars.typeDefs, readFileSync(join(__dirname, "typedefs.gql")).toString()],
    resolvers: [graphQLScalars.resolvers, require(join(__dirname, "resolvers"))],
});

// A fastify-hez érkezett kérést berakjuk a context-be
const context = (request) => {
    return {
        request,
    };
};

module.exports = (fastify) => {
    fastify.register(mercurius, {
        graphiql: true,
        schema,
        context,
    });
};
