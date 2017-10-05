import {
  FAIL_GET_COURSES_INFORMATIONS,
  FAIL_VALIDATE_SESSION_INTERNET,
  FILE_LOADED,
  RECEIVE_COURSES_INFORMATIONS,
  RECEIVE_SESSION_CONTENT,
  RECEIVE_VALIDATE_SESSION_INTERNET,
  REQUEST_COURSES_INFORMATIONS,
  REQUEST_VALIDATE_SESSION_INTERNET,
  SPOOL_TERMINATED
} from '../actions/actionCreators';
import receiveCourseInformationHandler from './handler/receiveCourseInformationHandler';

const DEFAULT_CONTENT_STATE = {
  isFetching: false,
  isErrorFetching: false,
  isValidating: false,
  isFailValidating: false,
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

    case RECEIVE_SESSION_CONTENT: {
      const {
        uuid: sessionUuid,
        contentUpdatedAt: sessionContentUpdatedAt,
        content: sessionContent
      } = action.payload.sessionContent;

      // Set loaded session content
      const loadedSession = {
        ...state.sessions[sessionUuid],
        content: sessionContent,
        contentUpdatedAt: sessionContentUpdatedAt
      };
      const currentSessions = { ...state.sessions };
      currentSessions[sessionUuid] = loadedSession;

      // Recreate sessionText spool without loaded session
      const sessionText = state.spool.sessionText.filter(
        uuid => uuid !== sessionUuid
      );

      return {
        ...state,
        sessions: currentSessions,
        spool: {
          ...state.spool,
          sessionText
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
      console.error(action);
      alert(action.type + '/' + action.payload.message);
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

    case REQUEST_VALIDATE_SESSION_INTERNET: {
      return { ...state, isValidating: true, isFailValidating: false };
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
          sessions: { ...currentSessions },
          isValidating: false,
          isFailValidating: false
        };
      }

      return { ...state };
    }

    case FAIL_VALIDATE_SESSION_INTERNET: {
      return { ...state, isFailValidating: true, isValidating: false };
    }

    default:
      return state;
  }
}
