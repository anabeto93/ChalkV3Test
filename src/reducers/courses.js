import {
  FAIL_GET_COURSES_INFORMATIONS,
  RECEIVE_COURSES_INFORMATIONS,
  REQUEST_COURSES_INFORMATIONS
} from '../actions/actionCreators';

export default function courses(
  state = {
    isFetching: false,
    items: []
  },
  action
) {
  switch (action.type) {
    case REQUEST_COURSES_INFORMATIONS: {
      return Object.assign({}, state, {
        isFetching: true
      });
    }

    case RECEIVE_COURSES_INFORMATIONS: {
      console.log('RECEIVE_COURSES_INFORMATIONS', action.payload);
      return Object.assign({}, state, {
        isFetching: false,
        items: action.payload.courses
      });
    }

    case FAIL_GET_COURSES_INFORMATIONS: {
      return Object.assign({}, state, {
        isFetching: false,
        items: []
      });
    }

    default:
      return state;
  }
}
