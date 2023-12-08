module.exports = fn => async (parent, params, context, info) => {
    const { payload } = await context.jwtVerify();

    context.user = payload;

    return fn(parent, params, context, info);
}