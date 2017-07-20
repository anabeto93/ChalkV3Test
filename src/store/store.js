import { applyMiddleware, compose, createStore } from 'redux';
import { persistStore, autoRehydrate } from 'redux-persist';
import thunkMiddleware from 'redux-thunk';

import appReducer from '../reducers';
import defaultState from './defaultState';
import { networkInterface } from '../graphql/client/GraphqlClient';

const store = createStore(
  appReducer,
  defaultState,
  compose(
    applyMiddleware(thunkMiddleware),
    autoRehydrate(),
    typeof window.__REDUX_DEVTOOLS_EXTENSION__ !== 'undefined'
      ? window.__REDUX_DEVTOOLS_EXTENSION__()
      : f => f
  )
);

persistStore(store);

// networkInterface need the store
networkInterface.use([
  {
    applyMiddleware(req, next) {
      // Create the header object if needed.
      if (!req.options.headers) {
        req.options.headers = {};
      }

      const userToken = store.getState().currentUser.token;
      req.options.headers.authorization = userToken
        ? `Bearer ${userToken}`
        : null;
      next();
    }
  }
]);

if (module.hot) {
  module.hot.accept('../reducers', () => {
    const nextGTR = require('../reducers');
    store.replaceReducer(nextGTR);
  });
}

export default store;
