import { combineReducers } from 'redux';

import GraphqlClient from '../graphql/client/GraphqlClient';

import courses from './courses';
import currentUser from './currentUser';
import network from './network';
import updates from './updates';

const appReducer = combineReducers({
  apollo: GraphqlClient.reducer(),
  courses,
  currentUser,
  network,
  updates
});

export default appReducer;
