const webpack = require('webpack');
const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const FixStyleOnlyEntriesPlugin = require('webpack-fix-style-only-entries');

const entryPoints = {
  polyfill: 'babel-polyfill',
  adminkit: './js/src/adminkit.js',
  style: './sass/style.scss',
};

const compiledEntries = {};

for (const prop in entryPoints) {
  compiledEntries[prop] = entryPoints[prop];
}

module.exports = (env, argv) => {
  const isDev = argv.mode === 'development';

  return {
    context: __dirname,
    entry: compiledEntries,

    output: {
      path: `${__dirname}/dist`,
      filename: '[name].js',
    },

    resolve: {
      extensions: ['.js', '.vue', '.json'],
      alias: {
        vue$: 'vue/dist/vue.esm.js',
      },
    },

    externals: {
      jquery: 'jQuery',
    },

    devtool: isDev ? 'source-map' : false,

    plugins: [
      // The below will shim global jquery, the first two lines will replace $/jQuery
      // when require('jquery') is called. The third line, which we probably will always
      // need with Drupal, then uses the window.jQuery instead of the module jquery when
      // require('jquery') is called
      new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
      }),

      new FixStyleOnlyEntriesPlugin(),
      new MiniCssExtractPlugin({ filename: '[name].css' }),
    ],
    optimization: {
      splitChunks: {
        cacheGroups: {
          commons: {
            name: 'commons',
            chunks: 'initial',
            // If you add more entryPoints, set minChunks to 2
            minChunks: 2,
          },
        },
      },
    },
    module: {
      rules: [
        {
          test: /\.vue$/,
          loader: 'vue-loader',
        },
        {
          enforce: 'pre',
          test: /\.js$/,
          exclude: /node_modules/,
          loader: 'eslint-loader',
        },
        {
          test: /\.js$/,
          // must add exceptions to this exlude statement for
          // anything that needs to be transpiled by babel
          exclude: [/node_modules\/(?!foundation-sites)/],
          loader: 'babel-loader',
        },
        {
          test: /\.(png|jpg|gif)$/,
          use: [{ loader: 'file-loader' }],
        },
        {
          test: /\.(woff2?|ttf|otf|eot|svg)$/,
          exclude: /node_modules/,
          loader: 'file-loader',
          options: {
            name: '[path][name].[ext]',
          },
        },
        {
          test: /\.(sa|sc|c)ss$/,
          use: [{
            loader: MiniCssExtractPlugin.loader,
          }, {
            loader: 'css-loader',
            options: {
              importLoaders: 1,
              sourceMap: isDev,
            },
          }, {
            loader: 'postcss-loader',
            options: {
              sourceMap: isDev,
            },
          }, {
            loader: 'sass-loader',
            options: {
              sassOptions: {
                includePaths: [path.resolve(__dirname, './node_modules/foundation-sites/scss')],
              },
              sourceMap: isDev,
            },
          }],
        },
      ],
    },
  };
};
