import { LOGIN_SUCCESS, RECEIVE_USER_INFORMATIONS } from '../actions/actionCreators';

export default function currentUser(
  state = { loginState: 'logged-out' },
  action
) {
  switch (action.type) {
    case LOGIN_SUCCESS: {
      return { loginState: 'logged' };
    }

    case RECEIVE_USER_INFORMATIONS: {
      return { ...state, ...action.payload.user };
    }

    default:
      return state;
  }
}
