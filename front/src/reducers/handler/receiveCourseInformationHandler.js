export default function receiveCourseInformationHandler(state, action) {
  const lastUpdatedAt = state.updatedAt;
  const items = action.payload.courses;
  const sessionText = state.spool.sessionText;
  const sessionFiles = state.spool.sessionFiles;

  const courses = {};
  const folders = {};
  const sessions = {};

  items.forEach(course => {
    courses[course.uuid] = course;

    course.folders.forEach(folder => {
      folders[folder.uuid] = { ...folder, courseUuid: course.uuid };

      folder.sessions.forEach(session => {
        sessions[session.uuid] = {
          ...session,
          courseUuid: course.uuid,
          folderUuid: folder.uuid
        };

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
    ...{
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
    null === lastUpdatedAt ||
    new Date(contentUpdatedAt) < new Date(lastUpdatedAt)
  );
}
