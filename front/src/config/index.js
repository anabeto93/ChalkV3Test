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
        dsn: 'https://c5f21bb1fb994d469dd773d94bef1c56:26bda1c60161453fb7185f2d8de4c72c@sentry.io/302241'
      }
    },
    apiPhoneNumber: '+233501333517'
  },
  default: {
    api: {
      endpoint: 'http://api.chalkboardeducation.vm/app_dev.php/api/graphql/'
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
        dsn: null
      }
    },
    apiPhoneNumber: '+233501333517'
  }
};

export default function getConfig() {
  return config[process.env.NODE_ENV] || config.default;
}
