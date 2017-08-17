export class CourseManager {
  /**
   * @param {array}  courseItems
   * @param {string} courseUuid
   * @return {Object|undefined}
   */
  getCourse(courseItems, courseUuid) {
    return courseItems.find(course => course.uuid === courseUuid);
  }

  /**
   * @param {Object} course
   * @param {string} folderUuid
   * @return {Object|undefined}
   */
  getFolderFromCourse(course, folderUuid) {
    if (!course.hasOwnProperty('folders')) return undefined;

    return course.folders.find(folder => folder.uuid === folderUuid);
  }

  /**
   * @param {Object} folder
   * @param {string} sessionUuid
   * @returns {Object|undefined}
   */
  getSessionFromFolder(folder, sessionUuid) {
    if (!folder.hasOwnProperty('sessions')) return undefined;

    return folder.sessions.find(session => session.uuid === sessionUuid);
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
