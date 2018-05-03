import { combineReducers } from 'redux';
import { asyncLocalStorage } from 'redux-persist/storages';

import GraphqlClient from '../graphql/client/GraphqlClient';

import content from './content';
import currentUser from './currentUser';
import network from './network';
import settings from './settings';
import updates from './updates';
import logout from './logout';

//Logout
export const USER_LOGOUT = '@@CHALKBOARDEDUCATION/LOGOUT';

const appReducer = combineReducers({
  apollo: GraphqlClient.reducer(),
  content,
  currentUser,
  network,
  updates,
  settings,
  logout
});

const rootReducer = (state, action) => {
  if (action.type === USER_LOGOUT) {
    Object.keys(state).forEach(key => {
      asyncLocalStorage.removeItem(`persist:${key}`);
    });

    state = undefined;
  }

  return appReducer(state, action);
};

export default rootReducer;
