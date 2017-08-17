import {
  FAIL_GET_COURSES_INFORMATIONS,
  RECEIVE_COURSES_INFORMATIONS,
  REQUEST_COURSES_INFORMATIONS
} from '../actions/actionCreators';

export default function courses(
  state = {
    isFetching: false,
    isErrorFetching: false,
    items: [],
    spool: {
      sessionText: [],
      sessionFiles: []
    }
  },
  action
) {
  switch (action.type) {
    case REQUEST_COURSES_INFORMATIONS: {
      return {
        ...state,
        isFetching: true,
        isErrorFetching: false
      };
    }

    case RECEIVE_COURSES_INFORMATIONS: {
      const previousCourseItems = state.items;
      const newCourseItems = action.payload.courses;

      // copy previous session content to new session content

      // forEach Session // when contentUpdatedAt > lastUpdate -> add session uuid to spool/sessionText
      // forEach SessionFile // when contentUpdatedAt > lastUpdate -> add file url to spool/sessionFiles

      return {
        ...state,
        isFetching: false,
        isErrorFetching: false,
        items: newCourseItems,
        spool: {
          sessionText: ['hello'],
          sessionFiles: ['hola', 'hello2']
        }
      };
    }

    case FAIL_GET_COURSES_INFORMATIONS: {
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
