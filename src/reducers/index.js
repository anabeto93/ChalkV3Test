import { combineReducers } from 'redux';
import storage from 'redux-persist/lib/storage';

import GraphqlClient from '../graphql/client/GraphqlClient';

import content from './content';
import currentUser from './currentUser';
import network from './network';
import settings from './settings';
import updates from './updates';

//Logout
export const USER_LOGOUT = '@@CHALKBOARDEDUCATION/LOGOUT';

const appReducer = combineReducers({
  apollo: GraphqlClient.reducer(),
  content,
  currentUser,
  network,
  updates,
  settings
});

const rootReducer = (state, action) => {
    if(action.type === USER_LOGOUT) {
        Object.keys(state).forEach(key => {
            storage.removeItem(`persist:${key}`);
        });

        state = undefined;
    }

    return appReducer(state, action);
};

export default rootReducer;
