import { applyMiddleware, compose, createStore } from 'redux';
import { autoRehydrate } from 'redux-persist';
import thunkMiddleware from 'redux-thunk';

import rootReducer from '../reducers';
import coursesIsFetchingSubscriber from '../subscriber/CoursesIsFetchingSubscriber';
import coursesSpoolSubscriber from '../subscriber/CoursesSpoolSubscriber';
import defaultState from './defaultState';
import { networkInterface } from '../graphql/client/GraphqlClient';

import { requestForcedUserLogout } from '../actions/actionCreators';

const store = createStore(
  rootReducer,
  defaultState,
  compose(
    applyMiddleware(thunkMiddleware),
    autoRehydrate(),
    process.env.NODE_ENV !== 'production' &&
    typeof window.__REDUX_DEVTOOLS_EXTENSION__ !== 'undefined'
      ? window.__REDUX_DEVTOOLS_EXTENSION__()
      : f => f
  )
);

// Init subscribers
store.subscribe(coursesIsFetchingSubscriber);
store.subscribe(coursesSpoolSubscriber);

// networkInterface need the store
networkInterface.use([
  {
    applyMiddleware(req, next) {
      // Create the header object if needed.
      if (!req.options.headers) {
        req.options.headers = {};
      }

      const userToken = store.getState().currentUser.token;
      const userTokenIssuedAt = store.getState().currentUser.tokenIssuedAt;
      req.options.headers.authorization = userToken
        ? `Bearer ${userToken + '~' + userTokenIssuedAt}`
        : null;
      next();
    }
  }
]);

//Afterware to handle errors
networkInterface.useAfter([
  {
    applyAfterware({ response }, next) {
      if (response.status === 402) {
        store.dispatch(requestForcedUserLogout());
      }

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
