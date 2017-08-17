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
      sessionFiles: [],
      total: 0
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
      // @todo: copy previous session content to new session content
      // const previousCourseItems = state.items;

      const lastUpdatedAt = state.updatedAt;
      const newCourseItems = action.payload.courses;
      const sessionText = state.spool.sessionText;
      const sessionFiles = state.spool.sessionFiles;

      newCourseItems.forEach(course => {
        course.folders.forEach(folder => {
          folder.sessions.forEach(session => {
            // Add session uuid to spool/sessionText
            if (
              isUpToDate(lastUpdatedAt, session.contentUpdatedAt) &&
              !sessionText.includes(session.uuid)
            ) {
              sessionText.push(session.uuid);
            }

            // Add file url to spool/sessionFiles
            session.files.forEach(file => {
              if (
                isUpToDate(lastUpdatedAt, file.updatedAt) &&
                !sessionFiles.includes(file.url)
              ) {
                sessionFiles.push(file.url);
              }
            });
          });
        });
      });

      return {
        ...state,
        // @todo: set new updateAt date provided by the backend
        // updatedAt: newCourseItems.updatedAt
        isFetching: false,
        isErrorFetching: false,
        items: newCourseItems,
        spool: {
          sessionText: sessionText,
          sessionFiles: sessionFiles,
          total: sessionText.length + sessionFiles.length
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

/**
 * @param {string} lastUpdatedAt
 * @param {string} contentUpdatedAt
 * @returns {boolean}
 */
function isUpToDate(lastUpdatedAt, contentUpdatedAt) {
  return (
    null === lastUpdatedAt ||
    new Date(contentUpdatedAt) < new Date(lastUpdatedAt)
  );
}
