export class CourseManager {
  static getCourse(courseItems, courseUuid) {
    return courseItems[courseUuid];
  }

  static getFolder(folderItems, folderUuid) {
    return folderItems[folderUuid];
  }

  static getSession(sessionsItems, sessionUuid) {
    return sessionsItems[sessionUuid];
  }

  static getQuestion(session, questionUuid) {
    return session.questions[questionUuid];
  }

  static getFoldersFromCourse(foldersItems, courseUuid) {
    let folders = {};
    for (let key in foldersItems) {
      if (foldersItems[key].courseUuid === courseUuid) {
        folders[key] = foldersItems[key];
      }
    }

    return folders;
  }

  static getSessionsFromFolder(sessionsItems, folderUuid) {
    let sessions = {};
    for (let key in sessionsItems) {
      if (sessionsItems[key].folderUuid === folderUuid) {
        sessions[key] = sessionsItems[key];
      }
    }

    return sessions;
  }

  static getNextSession(sessionsItems, session) {
    const courseUuid = session.courseUuid;
    const folderUuid = session.folderUuid;
    const currentPosition = session.position;

    for (let key in sessionsItems) {
      if (
        sessionsItems[key].courseUuid === courseUuid &&
        sessionsItems[key].folderUuid === folderUuid &&
        sessionsItems[key].position === currentPosition + 1
      ) {
        return sessionsItems[key];
      }
    }

    return null;
  }

  static getNextQuestion(session, questionUuid) {
    //Get all the keys in session
    let keys = Object.keys(session.questions);

    //Get the length of the session questions indexes
    const questionIndexes = keys.length - 1;

    //Get the current question index
    const questionIndex = keys.indexOf(questionUuid);

    //Not the last question and not the first question
    if (questionIndex < questionIndexes && questionIndex > 0) {
      return session.questions[keys[questionIndex + 1]];
    }

    return null;
  }
}

export default CourseManager;
