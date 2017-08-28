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
}

export default CourseManager;
