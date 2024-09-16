/**
 * Wrapper függvény a hitelesítéshez
 *
 * @param resolverFn - A "wrapper-elni" kívánt eredeti resolver függvény
 * @returns "Wrapper-elt" resolver függvény
 */
const authWrapper = (resolverFn) => async (parent, params, context, info) => {
    const payload = await context.request.jwtVerify();

    context.user = payload;

    // Eredeti resolver fv meghívása, azonban a context-ben már benne lesz a JWT payload (user adatok)
    return resolverFn(parent, params, context, info);
};

module.exports = authWrapper;
