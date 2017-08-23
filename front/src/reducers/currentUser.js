import { RECEIVE_USER_INFORMATIONS } from '../actions/actionCreators';
import {
  LOGIN_STATE_LOGGED_IN,
  LOGIN_STATE_LOGGED_OUT
} from '../store/defaultState';

export default function currentUser(
  state = { loginState: LOGIN_STATE_LOGGED_OUT },
  action
) {
  switch (action.type) {
    case RECEIVE_USER_INFORMATIONS: {
      return {
        ...state,
        ...action.payload.user,
        loginState: LOGIN_STATE_LOGGED_IN
      };
    }

    default:
      return state;
  }
}
