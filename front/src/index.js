import 'babel-polyfill';
import Raven from 'raven-js';
import React from 'react';
import ReactDOM from 'react-dom';
import injectTapEventPlugin from 'react-tap-event-plugin';

import App from './App';
import getConfig, { isProduction } from './config/index';
import './config/translations';
import './index.css';

import registerServiceWorker from './registerServiceWorker';

if (isProduction) {
  Raven.config(getConfig().externalServices.sentry.dsn).install();
}

injectTapEventPlugin();

ReactDOM.render(<App />, document.getElementById('root'));

registerServiceWorker();
