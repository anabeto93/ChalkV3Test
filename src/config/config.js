// Usage:
//  import getConfig from './config.js';
//  const endpoint = getConfig().api.endpoint;

const config = {
  production: {
    api: {
      endpoint: 'https://api.chalkboard.education/api/graphql/'
    }
  },
  default: {
    api: {
      endpoint: 'http://api.chalkboardeducation.dev/app_dev.php/api/graphql/'
    }
  }
};

export default function getConfig() {
  return config[process.env.NODE_ENV] || config.default;
}
