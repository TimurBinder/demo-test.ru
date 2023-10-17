'use strict';

const gulp = require('gulp')
const lazypipe = require('lazypipe')
const emptypipe = require('gulp-empty')
const gulpif = require('gulp-if')
const rename = require('gulp-rename')
const minify = require('gulp-minify')
const config = require('../config')

module.exports = {

  common: (options) => {
    return lazypipe()
      .pipe(() => gulpif(!!options.rename, rename(options.rename)))()
      .on('error', function() {
        this.emit('end')
      });
  },
  
   production: (options) => lazypipe()
    //.pipe(() => clean())
    .pipe(() => minify({
        ext: {
            min: '.min.js'
        }
    }))()
}
