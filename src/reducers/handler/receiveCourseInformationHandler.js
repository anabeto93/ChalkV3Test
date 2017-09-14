import getConfig from '../../config/index';

export default function receiveCourseInformationHandler(state, action) {
  const { courses: items, currentDate } = action.payload;
  const previousSessions = state.sessions;
  const lastUpdatedAt = state.updatedAt;
  const sessionText = state.spool.sessionText;
  const sessionFiles = state.spool.sessionFiles;
  const defaultFolder = getConfig().defaultFolder;

  const courses = {};
  const folders = {};
  const sessions = {};

  items.forEach(course => {
    courses[course.uuid] = {
      uuid: course.uuid,
      title: course.title,
      teacherName: course.teacherName,
      description: course.description,
      updatedAt: course.updatedAt
    };

    course.folders.forEach(folder => {
      const folderUuid =
        folder.uuid === defaultFolder
          ? `${course.uuid}_${folder.uuid}`
          : folder.uuid;

      folders[folderUuid] = {
        uuid: folder.uuid,
        title: folder.title,
        updatedAt: folder.updatedAt,
        courseUuid: course.uuid
      };

      folder.sessions.forEach((session, index) => {
        // copy previous session content to new session content
        const previousSession = previousSessions[session.uuid] || null;
        const previousSessionContent =
          null !== previousSession && previousSession.content
            ? previousSession.content
            : null;

        sessions[session.uuid] = {
          ...session,
          content: previousSessionContent,
          courseUuid: course.uuid,
          folderUuid: folder.uuid,
          position: index
        };

        if (
          !isUpToDate(lastUpdatedAt, session.contentUpdatedAt) &&
          !sessionText.includes(session.uuid)
        ) {
          sessionText.push(session.uuid);
        }

        // Add file url to spool/sessionFiles
        session.files.forEach(file => {
          if (
            !isUpToDate(lastUpdatedAt, file.updatedAt) &&
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
    // set new updatedAt date provided by the backend
    updatedAt: currentDate,
    isFetching: false,
    isErrorFetching: false,
    courses,
    folders,
    sessions,
    spool: {
      sessionText,
      sessionFiles,
      total: sessionText.length + sessionFiles.length
    }
  };
}

/**
 * @param {string} lastUpdatedAt
 * @param {string} contentUpdatedAt
 * @returns {boolean}
 */
function isUpToDate(lastUpdatedAt, contentUpdatedAt) {
  return (
    null !== lastUpdatedAt &&
    new Date(contentUpdatedAt) < new Date(lastUpdatedAt)
  );
}
