import moment from 'moment';

import {
  FAIL_GET_UPDATES,
  RECEIVE_UPDATES,
  REINIT_UPDATES,
  REQUEST_UPDATES
} from '../actions/actionCreators';

export default function updates(
  state = {
    isFetching: false,
    isErrorFetching: false,
    hasUpdates: false,
    dateLastCheck: null,
    size: 0
  },
  action
) {
  switch (action.type) {
    case REINIT_UPDATES: {
      return {
        ...state,
        isFetching: false,
        isErrorFetching: false,
        hasUpdates: false,
        size: 0
      };
    }

    case REQUEST_UPDATES: {
      return {
        ...state,
        isFetching: true,
        isErrorFetching: false
      };
    }

    case RECEIVE_UPDATES: {
      const { hasUpdates, size } = action.payload.updates;

      return {
        ...state,
        isFetching: false,
        isErrorFetching: false,
        hasUpdates: hasUpdates,
        dateLastCheck: moment(),
        size: size
      };
    }

    case FAIL_GET_UPDATES: {
      return {
        ...state,
        isFetching: false,
        isErrorFetching: true
      };
    }

    default:
      return state;
  }
}
