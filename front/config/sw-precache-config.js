module.exports = {
  staticFileGlobs: [
    'build/**/*.js',
    'build/**/*.css',
    'build/index.html'
  ],
  runtimeCaching: [{
    urlPattern: /\.*\.(?:svg|jpg|jpeg|gif|png)/g,
    handler: 'cacheFirst'
  }]
};
