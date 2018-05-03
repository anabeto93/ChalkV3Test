import {
  REQUEST_USER_LOGOUT,
  REQUEST_FORCED_USER_LOGOUT,
  CANCEL_USER_LOGOUT
} from '../actions/actionCreators';

export default function logout(
  state = {
    loggingOut: false,
    isForced: false
  },
  action
) {
  switch (action.type) {
    case REQUEST_USER_LOGOUT: {
      return {
        ...state,
        loggingOut: true
      };
    }

    case REQUEST_FORCED_USER_LOGOUT: {
      return {
        ...state,
        loggingOut: true,
        isForced: true
      };
    }

    case CANCEL_USER_LOGOUT: {
      return {
        ...state,
        loggingOut: false,
        isForced: false
      };
    }

    default:
      return state;
  }
}
