module.exports = {
    root: true,
    parserOptions: {
      parser: "babel-eslint",
      ecmaVersion: 2017,
      sourceType: 'module',
    },
    env: {
      browser: true,
    },
    extends: ["airbnb-base", "plugin:vue/recommended"],
    plugins: ['vue'],
    rules: {
      "no-param-reassign": 0
    }
};
