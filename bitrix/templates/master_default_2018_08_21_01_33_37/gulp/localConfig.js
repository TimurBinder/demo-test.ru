'use strict';

module.exports = {
  browserSync: {
    proxy: {
      target: 'master.lc',
      proxyReq: [(request) => {
        request.setHeader('Content-Type', 'text/plain; charset=windows-1251');
      }],
      proxyRes: [(response) => {
        response.push(' ')
      }]
    },
    port: 8080
  }
}
