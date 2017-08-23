export class CourseManager {
  getCourse(courseItems, courseUuid) {
    return courseItems[courseUuid];
  }

  getFolder(folderItems, folderUuid) {
    return folderItems[folderUuid];
  }

  getSession(sessionsItems, sessionUuid) {
    return sessionsItems[sessionUuid];
  }

  /**
   *
   * @param foldersItems
   * @param courseUuid
   * @returns {{}}
   */
  getFoldersFromCourse(foldersItems, courseUuid) {
    let folders = {};
    for (let key in foldersItems) {
      if (foldersItems[key].courseUuid === courseUuid) {
        folders[key] = foldersItems[key];
      }
    }

    return folders;
  }

  /**
   * @param sessionsItems
   * @param folderUuid
   * @returns {Object|undefined}
   */
  getSessionsFromFolder(sessionsItems, folderUuid) {
    let sessions = {};
    for (let key in sessionsItems) {
      if (sessionsItems[key].folderUuid === folderUuid) {
        sessions[key] = sessionsItems[key];
      }
    }

    return sessions;
  }

  /**
   * @param {array}  courseItems
   * @param {string} courseUuid
   * @param {string} sessionUuid
   * @returns {undefined|{Object}}
   */
  getSessionFromCourseAndSession(courseItems, courseUuid, sessionUuid) {
    const course = courseItems.find(course => course.uuid === courseUuid);
    let session = undefined;

    if (course !== undefined && course.hasOwnProperty('folders')) {
      course.folders.forEach(folder => {
        session = this.getSessionFromFolder(folder, sessionUuid);
      });
    }

    return session;
  }
}

export default new CourseManager();
