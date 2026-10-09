const path = require('path');

module.exports = {
  mode: 'production',
  entry: {
    main: './js/main.js',
    analytics: './js/analytics.js',
    boot: './js/boot.js',
    'design-system': './js/design-system.js',
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'dist/js'),
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: { presets: ['@babel/preset-env'] },
        },
      },
    ],
  },
};
