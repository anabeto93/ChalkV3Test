import React from 'react';
import { ApolloProvider } from 'react-apollo';
import ReactDOM from 'react-dom';

import App from './App';
import GraphqlClient from './config/GraphqlClient'
import registerServiceWorker from './registerServiceWorker';

import './index.css';

ReactDOM.render(
  <ApolloProvider client={GraphqlClient}>
    <App />
  </ApolloProvider>,
  document.getElementById('root')
);

registerServiceWorker();
