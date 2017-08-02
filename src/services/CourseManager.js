import store from "../store/store";

class CourseManager {
  /**
   * @param {Object} store
   */
  constructor(store) {
    this.store = store;
  }

  /**
   * @param {string} uuid
   * @return {Object|undefined}
   */
  getCourse(uuid) {
    let state = this.store.getState();

    if (!state.hasOwnProperty('courses')) return undefined;

    return state.courses.items.find((course) => course.uuid === uuid);
  }

  /**
   * @param {Object} course
   * @param {string} folderId
   * @return {Object|undefined}
   */
  getFolderFromCourse(course, folderId) {
    if (!course.hasOwnProperty('folders')) return undefined;

    return course.folders.find((folder) => folder.uuid === folderId);
  }

  /**
   * @param {Object} folder
   * @param {string} sessionId
   * @returns {Object|undefined}
   */
  getSessionFromFolder(folder, sessionId) {
    if (!folder.hasOwnProperty('sessions')) return undefined;

    return folder.sessions.find((session) => session.uuid === sessionId);
  }

  /**
   * @param {string} courseId
   * @param {string} sessionId
   * @returns {undefined|{Object}}
   */
  getSessionFromCourseIdAndSessionId(courseId, sessionId) {
    let state = this.store.getState();

    if (!state.hasOwnProperty('courses')) return undefined;

    let session = undefined;
    let course = state.courses.items.find(course => course.uuid === courseId);

    if (course !== undefined && course.hasOwnProperty('folders')) {
      course.folders.forEach(folder => {
        session = this.getSessionFromFolder(folder, sessionId);
      })
    }

    return session;
  }
}

export default new CourseManager(store);
