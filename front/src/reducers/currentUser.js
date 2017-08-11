import { RECEIVE_USER_INFORMATIONS } from '../actions/actionCreators';
import { LOGIN_STATE_LOGIN } from '../store/defaultState';

export default function currentUser(
  state = { loginState: 'logged-out' },
  action
) {
  switch (action.type) {
    case RECEIVE_USER_INFORMATIONS: {
      return {
        ...state,
        ...action.payload.user,
        loginState: LOGIN_STATE_LOGIN
      };
    }

    default:
      return state;
  }
}
