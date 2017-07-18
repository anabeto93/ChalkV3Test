import { applyMiddleware, compose, createStore } from 'redux';
import { persistStore, autoRehydrate } from 'redux-persist';
import thunkMiddleware from 'redux-thunk';

import appReducer from '../reducers';
import defaultState from './defaultState';

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

if (module.hot) {
  module.hot.accept('../reducers', () => {
    const nextGTR = require('../reducers');
    store.replaceReducer(nextGTR);
  });
}

export default store;
