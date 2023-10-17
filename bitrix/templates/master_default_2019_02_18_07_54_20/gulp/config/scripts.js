'use strict';

const path = require('path')

module.exports = {
  main: {
    paths: {
      src: path.resolve('./resources/scripts/**/*.js'),
      dest: path.resolve('./assets/js')
    },
    prodPaths: {
      src: ['./assets/js/**/*.js', '!./assets/js/**/*.min.js'],
      dest: path.resolve('./assets/js')
    }
  },
  componenets: {
    paths: {
      src: path.resolve('./components/**/*.dev.js'),
      dest: path.resolve('./components/')
    },
     prodPaths: {
      src: path.resolve('./components/**/script.js'),
      dest: path.resolve('./components')
    },
    rename: function(path) {
      path.basename = path.basename.replace('.dev', '')
    }
  }
}
