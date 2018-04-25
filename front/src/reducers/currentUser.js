import {
  FAIL_GET_USER_INFORMATIONS,
  RECEIVE_USER_INFORMATIONS,
  REQUEST_USER_INFORMATIONS
} from '../actions/actionCreators';
import {
  LOGIN_STATE_LOGGED_IN,
  LOGIN_STATE_LOGGED_OUT
} from '../store/defaultState';

export default function currentUser(
  state = {
    token: null,
    tokenIssuedAt: null,
    loginState: LOGIN_STATE_LOGGED_OUT,
    uuid: null,
    firstName: null,
    lastName: null,
    country: null,
    phoneNumber: null,
    locale: null
  },
  action
) {
  switch (action.type) {
    case REQUEST_USER_INFORMATIONS: {
      return {
        ...state,
        token: action.payload.token,
        loginState: LOGIN_STATE_LOGGED_OUT
      };
    }

    case RECEIVE_USER_INFORMATIONS: {
      const {
        uuid,
        firstName,
        lastName,
        country,
        phoneNumber,
        locale
      } = action.payload;

      return {
        ...state,
        uuid,
        firstName,
        lastName,
        country,
        phoneNumber,
        locale,
        loginState: LOGIN_STATE_LOGGED_IN
      };
    }

    case FAIL_GET_USER_INFORMATIONS: {
      return {
        ...state,
        token: null,
        loginState: LOGIN_STATE_LOGGED_OUT
      };
    }

    default:
      return state;
  }
}
