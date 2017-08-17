import { combineReducers } from 'redux';

import courses from './courses';
import currentUser from './currentUser';
import updates from './updates';
import settings from './settings';
import GraphqlClient from '../graphql/client/GraphqlClient';

const appReducer = combineReducers({
  apollo: GraphqlClient.reducer(),
  courses,
  currentUser,
  updates,
  settings
});

export default appReducer;
