import {
  FAIL_GET_UPDATES,
  RECEIVE_UPDATES,
  REQUEST_UPDATES
} from '../actions/actionCreators';

export default function updates(
  state = {
    isFetching: false,
    hasUpdates: false,
    dateLastCheck: null,
    sizeToDownload: 0
  },
  action
) {
  switch (action.type) {
    case REQUEST_UPDATES: {
      return Object.assign({}, state, {
        isFetching: true
      });
    }

    case RECEIVE_UPDATES: {
      console.log('RECEIVE_UPDATES', action.payload);
      return Object.assign({}, state, {
        isFetching: false
      });
    }

    case FAIL_GET_UPDATES: {
      return Object.assign({}, state, {
        isFetching: false
      });
    }

    default:
      return state;
  }
}
