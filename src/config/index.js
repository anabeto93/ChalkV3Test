// Usage:
//  import getConfig from 'path/to/config';
//  const endpoint = getConfig().api.endpoint;

const config = {
  production: {
    api: {
      endpoint: 'https://api.chalkboard.education/api/graphql/'
    },
    appName: 'Chalkboard Education',
    appPrivateKey: 'i5Rj10bMdZGeK9fWg6qhQkmACn8YrPpBstuv2DwNXVxayz7EFUcH3JLS4T',
    apiPrivateKey: '0C3abke2ty4Ah6RdDpSUKJHL7YnxfEjiP9FwQvX8rVgmqNscz5uBGWZ1TM',
    updates: {
      intervalInSeconds: 86400
    },
    defaultFolder: 'default',
    externalServices: {
      sentry: {
        dsn: 'https://ec87becf128a4e599caecca6ccdf410d@sentry.io/229837'
      }
    },
    apiPhoneNumber: '+233200659986'
  },
  default: {
    api: {
      endpoint: 'http://api.chalkboardeducation.dev/app_dev.php/api/graphql/'
    },
    appName: 'Chalkboard Development',
    appPrivateKey: 'i5Rj10bMdZGeK9fWg6qhQkmACn8YrPpBstuv2DwNXVxayz7EFUcH3JLS4T',
    apiPrivateKey: '0C3abke2ty4Ah6RdDpSUKJHL7YnxfEjiP9FwQvX8rVgmqNscz5uBGWZ1TM',
    updates: {
      intervalInSeconds: 20
    },
    defaultFolder: 'default',
    externalServices: {
      sentry: {
        dsn: 'https://ec87becf128a4e599caecca6ccdf410d@sentry.io/229837'
      }
    },
    apiPhoneNumber: '+233200659986'
  }
};

export const isProduction = process.env.NODE_ENV === 'production';

export default function getConfig() {
  return config[process.env.NODE_ENV] || config.default;
}
