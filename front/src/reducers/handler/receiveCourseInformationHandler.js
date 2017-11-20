import getConfig from '../../config/index';
import { QUESTIONS_MOCK } from '../../services/quiz/QuestionsMock';

export default function receiveCourseInformationHandler(state, action) {
  const { courses: items, currentDate } = action.payload;
  const previousSessions = state.sessions;
  const previousCourses = state.courses;
  const lastUpdatedAt = state.updatedAt;
  const sessionText = state.spool.sessionText;
  const sessionFiles = state.spool.sessionFiles;
  const defaultFolder = getConfig().defaultFolder;

  const courses = {};
  const folders = {};
  const sessions = {};

  items.forEach(course => {
    const isNewCourse = undefined === previousCourses[course.uuid];

    courses[course.uuid] = {
      uuid: course.uuid,
      title: course.title,
      teacherName: course.teacherName,
      description: course.description,
      updatedAt: course.updatedAt
    };

    course.folders.forEach(folder => {
      const isDefault = folder.uuid === defaultFolder;
      const folderUuid = isDefault
        ? `${course.uuid}_${folder.uuid}`
        : folder.uuid;

      folders[folderUuid] = {
        uuid: folderUuid,
        isDefault,
        title: folder.title,
        updatedAt: folder.updatedAt,
        courseUuid: course.uuid
      };

      folder.sessions.forEach((session, position) => {
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
          questions: QUESTIONS_MOCK, // will be removed later
          folderUuid,
          position
        };

        if (
          (isNewCourse ||
            !isUpToDate(lastUpdatedAt, session.contentUpdatedAt)) &&
          !sessionText.includes(session.uuid)
        ) {
          sessionText.push(session.uuid);
        }

        // Add file url to spool/sessionFiles
        session.files.forEach(file => {
          if (
            (isNewCourse || !isUpToDate(lastUpdatedAt, file.updatedAt)) &&
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
