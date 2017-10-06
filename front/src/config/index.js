// Usage:
//  import getConfig from 'path/to/config';
//  const endpoint = getConfig().api.endpoint;

const config = {
  production: {
    api: {
      endpoint: 'https://api.chalkboard.education/api/graphql/'
    },
    appName: 'Chalkboard Education',
    updates: {
      intervalInSeconds: 86400
    },
    appPrivateKey: '',
    apiPrivateKey: '0C3abke2ty4Ah6RdDpSUKJHL7YnxfEjiP9FwQvX8rVgmqNscz5uBGWZ1TM',
    defaultFolder: 'default'
  },
  default: {
    api: {
      endpoint: 'http://api.chalkboardeducation.dev/app_dev.php/api/graphql/'
    },
    appName: 'Chalkboard Development',
    updates: {
      intervalInSeconds: 20
    },
    appPrivateKey: '',
    apiPrivateKey: '0C3abke2ty4Ah6RdDpSUKJHL7YnxfEjiP9FwQvX8rVgmqNscz5uBGWZ1TM',
    defaultFolder: 'default'
  }
};

export default function getConfig() {
  return config[process.env.NODE_ENV] || config.default;
}
