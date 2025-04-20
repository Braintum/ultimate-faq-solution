const path = require('path');

module.exports = {
  entry: './src/index.js',
  output: {
    path: path.resolve(__dirname, 'assets/dist'),
    filename: 'bundle.js',
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
        },
      },
      {
        test: /\.css$/, // Add rule for CSS files
        oneOf: [
          {
            resourceQuery: /shadow/, // CSS files imported with "?shadow"
            use: ['css-loader'], // Load CSS as a string for Shadow DOM
          },
          {
            use: ['style-loader', 'css-loader'], // Inject CSS into the DOM for global styles
          },
        ],
      },
    ],
  },
  resolve: {
    extensions: ['.js', '.jsx'],
  },
  mode: 'production',
};
