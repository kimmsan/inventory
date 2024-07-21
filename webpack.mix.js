const path = require('path')
const fs = require('fs-extra')
const mix = require('laravel-mix')
require('laravel-mix-versionhash')
mix
  .js('resources/js/app.js', 'public/dist/js')
  .sass('resources/sass/app.scss', 'public/dist/css')
  .disableNotifications()

if (mix.inProduction()) {
  mix
    .versionHash()
} else {
  mix.sourceMaps()
}

mix.webpackConfig({
  module: {
    rules: [
      {
        test: /\.wav$/i,
        use: [
          {
            loader: 'file-loader',
          },
        ],
      },
    ],
  },
  resolve: {
    extensions: ['.js', '.json', '.vue'],
    alias: {
      '~': path.join(__dirname, './resources/js')
    }
  },
  output: {
    chunkFilename: 'dist/js/[chunkhash].js',
    path: mix.config.hmr
      ? '/'
      : path.resolve(__dirname, mix.inProduction() ? './public/build' : './public')
  }
})

mix.then(() => {
  if (mix.inProduction()) {
    process.nextTick(() => publishAssets())
  }
})

function publishAssets () {
  const publicDir = path.resolve(__dirname, './public')

  fs.removeSync(path.join(publicDir, 'dist'))
  fs.copySync(path.join(publicDir, 'build', 'dist'), path.join(publicDir, 'dist'))
  fs.removeSync(path.join(publicDir, 'build'))
}
