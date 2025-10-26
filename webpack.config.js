const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const isProd = process.env.NODE_ENV === 'production';

module.exports = {
  // ✅ Multiple entry points
  entry: {
    main: './src/index.js', // existing build
    admin: './src/admin.js', // visual builder
  },

  output: (pathData) => {
    // ✅ Dynamically choose output based on entry
    const name = pathData.chunk.name;
    if (name === 'admin') {
      return {
        path: path.resolve(__dirname, 'inc/admin/assets/js'),
        filename: '[name].js',
        clean: true,
      };
    }
    // default output (for main)
    return {
      path: path.resolve(__dirname, 'assets/dist'),
      filename: 'bundle.js',
      clean: true,
    };
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
              isProd ? MiniCssExtractPlugin.loader : 'style-loader',
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
    // ✅ Extract CSS only in production builds
    new MiniCssExtractPlugin({
      // The CSS output directory structure
      filename: (pathData) => {
        const name = pathData.chunk.name;
        if (name === 'admin') {
          return '../../css/[name].css'; // ./inc/admin/assets/css/admin.css
        }
        return '../../css/[name].css'; // ./assets/css/main.css (if you want)
      },
    }),
  ],

  mode: isProd ? 'production' : 'development',
  devtool: isProd ? false : 'source-map',

  optimization: {
    splitChunks: {
      chunks: 'all',
    },
  },
};
