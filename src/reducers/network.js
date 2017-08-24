import { SET_NETWORK_STATUS } from '../actions/actionCreators';

export default function currentUser(state = { isOnline: true }, action) {
  switch (action.type) {
    case SET_NETWORK_STATUS: {
      return { isOnline: action.payload };
    }

    default:
      return state;
  }
}
