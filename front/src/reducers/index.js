import { combineReducers } from 'redux';

import courses from './courses';
import currentUser from './currentUser';
import updates from './updates';
import routing from './routing'
import GraphqlClient from '../graphql/client/GraphqlClient';

const appReducer = combineReducers({
  apollo: GraphqlClient.reducer(),
  courses,
  currentUser,
  updates,
  routing
});

export default appReducer;
