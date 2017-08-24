import {
  FAIL_GET_COURSES_INFORMATIONS,
  FILE_LOADED,
  RECEIVE_COURSES_INFORMATIONS,
  RECEIVE_VALIDATE_SESSION_INTERNET,
  REQUEST_COURSES_INFORMATIONS,
  SPOOL_TERMINATED
} from '../actions/actionCreators';
import receiveCourseInformationHandler from './handler/receiveCourseInformationHandler';

const DEFAULT_CONTENT_STATE = {
  isFetching: false,
  isErrorFetching: false,
  courses: {},
  folders: {},
  sessions: {},
  items: [],
  spool: {
    sessionText: [],
    sessionFiles: [],
    total: 0
  }
};

export default function content(state = DEFAULT_CONTENT_STATE, action) {
  switch (action.type) {
    case FILE_LOADED: {
      const sessionFiles = state.spool.sessionFiles.filter(
        file => file !== action.payload.file
      );

      return {
        ...state,
        spool: {
          ...state.spool,
          sessionFiles
        }
      };
    }

    case REQUEST_COURSES_INFORMATIONS: {
      return {
        ...state,
        isFetching: true,
        isErrorFetching: false
      };
    }

    case RECEIVE_COURSES_INFORMATIONS: {
      return receiveCourseInformationHandler(state, action);
    }

    case FAIL_GET_COURSES_INFORMATIONS: {
      return {
        ...state,
        isFetching: false,
        isErrorFetching: true
      };
    }

    case SPOOL_TERMINATED: {
      // Reset spool total to zero
      return {
        ...state,
        spool: {
          ...state.spool,
          total: 0
        }
      };
    }

    case RECEIVE_VALIDATE_SESSION_INTERNET: {
      const validatedSession = {
        ...state.sessions[action.payload.sessionUuid],
        validated: true
      };
      const currentSessions = { ...state.sessions };
      currentSessions[validatedSession.uuid] = validatedSession;

      if (validatedSession !== undefined) {
        return {
          ...state,
          sessions: { ...currentSessions }
        };
      }

      return { ...state };
    }

    default:
      return state;
  }
}
