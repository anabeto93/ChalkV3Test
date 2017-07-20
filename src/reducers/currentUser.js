import { LOGIN_SUCCESS } from '../actions/actionCreators';

export default function currentUser(
  state = { loginState: 'logged-out' },
  action
) {
  switch (action.type) {
    case LOGIN_SUCCESS: {
      return { loginState: 'logged' };
    }

    default:
      return state;
  }
}
