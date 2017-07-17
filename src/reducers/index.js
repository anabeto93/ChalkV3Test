import { combineReducers } from 'redux';

import courses from './courses';
import currentUser from './currentUser';
import GraphqlClient from '../graphql/client/GraphqlClient';

const appReducer = combineReducers({
  courses,
  currentUser,
  apollo: GraphqlClient.reducer()
});

export default appReducer;
