'use strict';

const path = require('path')

const autoprefixerOptions = {
  'browsers': ['last 50 versions', 'ie >= 9']
}

const sassOptions = {
  includePaths: [
    './node_modules/bootstrap-sass/assets/stylesheets/'
  ]
}

module.exports = {

  resources: {
    paths: {
      src: path.resolve('./resources/styles/**/*.scss'),
      dest: path.resolve('./assets/css')
    },
    prodPaths: {
      src: ['./assets/css/**/*.css', '!./assets/css/**/*.min.css'],
      dest: path.resolve('./assets/css')
    },
    autoprefixer: autoprefixerOptions,
    sass: sassOptions
  },

  components: {
    paths: {
      src: path.resolve('./components/**/*.scss'),
      dest: path.resolve('./components'),
    },
    prodPaths: {
      src: path.resolve('./components/**/*.css'),
      dest: path.resolve('./components')
    },
    autoprefixer: autoprefixerOptions
  },

  template: {
    paths: {
      src: path.resolve('./*.scss'),
      dest: path.resolve('./'),
    },
    prodPaths: {
      src: path.resolve('./*.css'),
      dest: path.resolve('./'),
    },
    autoprefixer: autoprefixerOptions
  }

}
