'use strict';

const gulp = require('gulp');
const gulpif = require('gulp-if')
const es = require('event-stream')
const rename = require('gulp-rename')
const config = require('../config')
const watch = require('gulp-watch')
const scriptsTasks = require('../helpers/scripts_tasks')

var srcs = []

Object.keys(config.scripts).forEach((key) => {
  const scriptConfig = config.scripts[key];
  const taskName = 'scripts.' + key;

  gulp.task(taskName, () => {

    return gulp.src(scriptConfig.paths.src)
      .pipe(scriptsTasks.common(scriptConfig))
      .pipe(gulpif(!!scriptConfig.rename, rename(scriptConfig.rename)))
      .pipe(gulp.dest(scriptConfig.paths.dest))
  })

  gulp.task(taskName + ':watch', [taskName], () => {
    gulp.watch(scriptConfig.paths.src, () => {
      gulp.start(taskName);
    })
  })

  srcs.push(scriptConfig.paths.src);

})

gulp.task('scripts', () => {
  var streams = [],
    scriptConfig;

  Object.keys(config.scripts).forEach((key) => {

    scriptConfig = config.scripts[key];
    streams.push(
      gulp.src(scriptConfig.paths.src)
      .pipe(scriptsTasks.common(scriptConfig))
      .pipe(gulp.dest(scriptConfig.paths.dest))
    );

  });

  return es.merge.call(undefined, streams);
})

gulp.task('scripts:watch', ['scripts'], () => {
  watch(srcs, () => {
    gulp.start('scripts');
  })
})

gulp.task('scripts:production', ['scripts'], () => {
  var streams = [],
    scriptConfig;

  Object.keys(config.scripts).forEach((key) => {

    scriptConfig = config.scripts[key];
    
    if (!scriptConfig.prodPaths) {
      return true;
    }
    
    streams.push(
      gulp.src(scriptConfig.prodPaths.src)
        .pipe(scriptsTasks.production(scriptConfig))
        .pipe(gulp.dest(scriptConfig.prodPaths.dest))
    );

  });

  return es.merge.call(undefined, streams);
})
