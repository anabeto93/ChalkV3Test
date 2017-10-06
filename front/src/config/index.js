// Usage:
//  import getConfig from 'path/to/config';
//  const endpoint = getConfig().api.endpoint;

const config = {
  production: {
    api: {
      endpoint: 'https://api.chalkboard.education/api/graphql/'
    },
    appName: 'Chalkboard Education',
    privateKey: 'i5Rj10bMdZGeK9fWg6qhQkmACn8YrPpBstuv2DwNXVxayz7EFUcH3JLS4T',
    updates: {
      intervalInSeconds: 86400
    },
    defaultFolder: 'default'
  },
  default: {
    api: {
      endpoint: 'http://api.chalkboardeducation.dev/app_dev.php/api/graphql/'
    },
    appName: 'Chalkboard Development',
    privateKey: 'i5Rj10bMdZGeK9fWg6qhQkmACn8YrPpBstuv2DwNXVxayz7EFUcH3JLS4T',
    updates: {
      intervalInSeconds: 20
    },
    defaultFolder: 'default',
    apiPhoneNumber: '+233200659986'
  }
};

export default function getConfig() {
  return config[process.env.NODE_ENV] || config.default;
}
