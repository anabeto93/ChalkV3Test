import {
  DONE_VALIDATE_SESSION,
  FAIL_GET_COURSES_INFORMATIONS,
  FAIL_VALIDATE_SESSION_INTERNET,
  FILE_LOADED,
  RECEIVE_COURSES_INFORMATIONS,
  RECEIVE_SESSION_CONTENT,
  RECEIVE_VALIDATE_SESSION_INTERNET,
  RECEIVE_VALIDATE_SESSION_SMS,
  REINIT_CONTENT_STATES,
  REQUEST_COURSES_INFORMATIONS,
  REQUEST_VALIDATE_SESSION_INTERNET,
  SPOOL_TERMINATED,
  SET_USER_ANSWER
} from '../actions/actionCreators';
import receiveCourseInformationHandler from './handler/receiveCourseInformationHandler';

const DEFAULT_CONTENT_STATE = {
  isFetching: false,
  isErrorFetching: false,
  isSessionValidating: false,
  isSessionFailValidating: false,
  isSessionValidated: false,
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
      return {
        ...state,
        isSessionValidating: true,
        isSessionFailValidating: false
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
          sessions: { ...currentSessions },
          isSessionValidating: false,
          isSessionFailValidating: false
        };
      }

      return { ...state };
    }

    case RECEIVE_VALIDATE_SESSION_SMS: {
      const validatedSession = {
        ...state.sessions[action.payload.sessionUuid],
        validated: true
      };

      const currentSessions = { ...state.sessions };
      currentSessions[validatedSession.uuid] = validatedSession;

      return {
        ...state,
        sessions: { ...currentSessions },
        isSessionValidating: false,
        isSessionFailValidating: false,
        isSessionValidated: true
      };
    }

    case FAIL_VALIDATE_SESSION_INTERNET: {
      return {
        ...state,
        isSessionFailValidating: true,
        isSessionValidating: false
      };
    }

    case DONE_VALIDATE_SESSION: {
      return {
        ...state,
        isSessionValidated: false,
        isSessionValidating: false,
        isSessionFailValidating: false
      };
    }

    case REINIT_CONTENT_STATES: {
      return {
        ...state,
        isFetching: false,
        isErrorFetching: false,
        isSessionValidating: false,
        isSessionFailValidating: false,
        isSessionValidated: false
      };
    }

    case SET_USER_ANSWER: {
      const { sessionUuid, questionIndex, answerIndex } = action.payload;
      let userAnsweredQuestion;

      if (state.sessions[sessionUuid].questions[questionIndex].isMultiple) {
        userAnsweredQuestion = {
          ...state.sessions[sessionUuid].questions[questionIndex],
          userAnswer: answerIndex
        };
      } else {
        userAnsweredQuestion = {
          ...state.sessions[sessionUuid].questions[questionIndex],
          userAnswer: answerIndex
        };
      }

      const currentQuestions = {
        ...state.sessions[sessionUuid].questions
      };
      currentQuestions[questionIndex] = userAnsweredQuestion;

      const currentSession = { ...state.sessions[sessionUuid] };
      currentSession.questions = currentQuestions;

      const currentSessions = { ...state.sessions };
      currentSessions[sessionUuid] = currentSession;

      return {
        ...state,
        sessions: { ...currentSessions }
      };
    }

    default:
      return state;
  }
}
