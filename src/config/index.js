// Usage:
//  import getConfig from 'path/to/config';
//  const endpoint = getConfig().api.endpoint;

const config = {
  production: {
    api: {
      endpoint: 'https://api.chalkboard.education/api/graphql/'
    },
    updates: {
      intervalInSeconds: 86400
    },
    defaultFolder: 'default'
  },
  default: {
    api: {
      endpoint: 'http://api.chalkboardeducation.dev/app_dev.php/api/graphql/'
    },
    appName: 'Chalkboard Education',
    updates: {
      intervalInSeconds: 20
    },
    defaultFolder: 'default'
  }
};

export default function getConfig() {
  return config[process.env.NODE_ENV] || config.default;
}
