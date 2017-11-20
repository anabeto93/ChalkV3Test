import 'babel-polyfill';
import Raven from 'raven-js';
import React from 'react';
import ReactDOM from 'react-dom';
import injectTapEventPlugin from 'react-tap-event-plugin';

import App from './App';
import getConfig from './config/index';
import './config/translations';
import './index.css';
import registerServiceWorker from './registerServiceWorker';

const sentryDsn = getConfig().externalServices.sentry.dsn;
if (null !== sentryDsn) {
  Raven.config(sentryDsn).install();
}

injectTapEventPlugin();

ReactDOM.render(<App />, document.getElementById('root'));

registerServiceWorker();
