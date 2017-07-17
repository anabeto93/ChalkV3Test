import { applyMiddleware, compose, createStore } from 'redux';
import thunkMiddleware from 'redux-thunk';

import appReducer from '../reducers';
import defaultState from './defaultState';
import GraphqlClient from '../graphql/GraphqlClient';

const store = createStore(
  appReducer,
  defaultState,
  compose(
    applyMiddleware(thunkMiddleware, GraphqlClient.middleware()),
    typeof window.__REDUX_DEVTOOLS_EXTENSION__ !== 'undefined'
      ? window.__REDUX_DEVTOOLS_EXTENSION__()
      : f => f
  )
);

if (module.hot) {
  module.hot.accept('../reducers', () => {
    const nextGTR = require('../reducers');
    store.replaceReducer(nextGTR);
  });
}

export default store;
