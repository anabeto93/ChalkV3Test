import { combineReducers } from 'redux';

import GraphqlClient from '../graphql/client/GraphqlClient';

import content from './content';
import currentUser from './currentUser';
import network from './network';
import settings from './settings';
import updates from './updates';

const appReducer = combineReducers({
  apollo: GraphqlClient.reducer(),
  content,
  currentUser,
  network,
  updates,
  settings
});

export default appReducer;
