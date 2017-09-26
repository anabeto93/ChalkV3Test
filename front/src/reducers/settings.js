import {
  RECEIVE_USER_INFORMATIONS,
  SETTINGS_SET_LOCALE
} from '../actions/actionCreators';

export default function settings(state = { settings }, action) {
  switch (action.type) {
    case SETTINGS_SET_LOCALE:
      return { ...state, locale: action.payload.locale };
    case RECEIVE_USER_INFORMATIONS:
      return { ...state, locale: action.payload.locale };
    default:
      return state;
  }
}
