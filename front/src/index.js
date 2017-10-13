import 'babel-polyfill';
import React from 'react';
import ReactDOM from 'react-dom';
import injectTapEventPlugin from 'react-tap-event-plugin';
import Raven from 'raven-js';

import App from './App';
import './config/translations';
import './index.css';
import getConfig from './config/index';

import registerServiceWorker from './registerServiceWorker';

Raven.config(getConfig().externalServices.sentry.dsn).install();

injectTapEventPlugin();

ReactDOM.render(<App />, document.getElementById('root'));

registerServiceWorker();
