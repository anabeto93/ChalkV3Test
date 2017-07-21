import moment from 'moment';

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
    size: 0
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
      const { hasUpdates, size } = action.payload.updates;
      return Object.assign({}, state, {
        isFetching: false,
        hasUpdates: hasUpdates,
        dateLastCheck: moment(),
        size: size
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
