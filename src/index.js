import 'babel-polyfill';
import React from 'react';
import ReactDOM from 'react-dom';
import injectTapEventPlugin from 'react-tap-event-plugin';

import App from './App';
import './config/translations';
import './index.css';

import registerServiceWorker from './registerServiceWorker';

// Init the clock
import './services/updates/clock';

injectTapEventPlugin();

ReactDOM.render(<App />, document.getElementById('root'));

registerServiceWorker();
