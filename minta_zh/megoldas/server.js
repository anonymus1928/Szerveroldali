require("dotenv").config();
const fastify = require("fastify")({
    logger: {
        level: "info",
        file: "fastify.log",
    },
});
const autoload = require("@fastify/autoload");
const chalk = require("chalk");
const AutoTester = require("./test/inject");
const { join } = require("path");
const registerGraphQL = require("./graphql");

const port = process.env.PORT || 4000;
const secret = process.env.JWT_SECRET || "secret";

// Hitelesítés
fastify.register(require("@fastify/jwt"), {
    secret,
});

fastify.decorate("auth", async function (request, reply) {
    try {
        await request.jwtVerify();
    } catch (err) {
        reply.send(err);
    }
});

// GraphQL regisztrálása (mercurius modul)
registerGraphQL(fastify);

// Route-ok automatikus betöltése
fastify.register(autoload, {
    dir: join(__dirname, "routes"),
});

// App indítása a megadott porton
fastify.listen({ port }, (err, address) => {
    if (err) throw err;

    console.log(`A Fastify app fut: ${chalk.yellow(address)}`);
    console.log(`GraphQL végpont: ${chalk.yellow(`${address}/graphql`)}`);
    console.log(`GraphiQL végpont: ${chalk.yellow(`${address}/graphiql`)}`);

    // FONTOS! Erre szükség van, hogy az automata tesztelő megfelelően tudjon inicializálni!
    // Ehhez a sorhoz ne nyúlj a munkád közben: hagyd legalul, ne vedd ki, ne kommenteld ki,
    // különben elrontod az automata tesztelő működését!
    AutoTester.handleStart();
});
