const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');

const isProd = process.env.NODE_ENV === 'production';

module.exports = {
  // Multiple entry points
  entry: {
    main: './src/index.js', // faq assistant.
    admin: './src/admin.js', // visual builder
  },

  output: {
    path: path.resolve(__dirname, 'assets/dist'),
    filename: (pathData) => {
      // Dynamically choose output filename based on entry
      const name = pathData.chunk.name;
      if (name === 'admin') {
        return '[name].js';
      }
      return 'bundle.js';
    },
    clean: true,
  },

  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              ['@babel/preset-env', { targets: 'defaults' }],
              ['@babel/preset-react', { runtime: 'automatic' }],
            ],
          },
        },
      },
      {
        test: /\.css$/,
        oneOf: [
          {
            resourceQuery: /shadow/, // e.g. import 'style.css?shadow'
            use: ['css-loader'], // load CSS as string (for shadow DOM)
          },
          {
            use: [
              MiniCssExtractPlugin.loader,
              'css-loader',
              'postcss-loader', // Tailwind + Autoprefixer
            ],
          },
        ],
      },
    ],
  },

  resolve: {
    extensions: ['.js', '.jsx'],
  },

  plugins: [
    // Extract CSS in both development and production
    new MiniCssExtractPlugin({
      // The CSS output directory structure
      filename: (pathData) => {
        const name = pathData.chunk.name;
        if (name === 'admin') {
          return '../css/[name].css';
        }
        return '../css/floatingbutton.css';
      },
    }),
  ],

  mode: 'production',
  devtool: false,

  optimization: {
    splitChunks: false, // Disable code splitting to avoid filename conflicts
    minimizer: [
      '...',  // Keep the default JS minimizer (Terser)
      new CssMinimizerPlugin(),
    ],
    minimize: true,
  },
};
