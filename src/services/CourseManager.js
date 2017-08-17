import store from '../store/store';

class CourseManager {
  /**
   * @param {Object} store
   */
  constructor(store) {
    this.store = store;
  }

  /**
   * @param {string} courseUuid
   * @return {Object|undefined}
   */
  getCourse(courseUuid) {
    let state = this.store.getState();

    if (!state.hasOwnProperty('courses')) return undefined;

    return state.courses.items.find(course => course.uuid === courseUuid);
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
   * @param {string} courseUuid
   * @param {string} sessionUuid
   * @returns {undefined|{Object}}
   */
  getSessionFromCourseAndSession(courseUuid, sessionUuid) {
    let state = this.store.getState();

    if (!state.hasOwnProperty('courses')) return undefined;

    let session = undefined;
    let course = state.courses.items.find(course => course.uuid === courseUuid);

    if (course !== undefined && course.hasOwnProperty('folders')) {
      course.folders.forEach(folder => {
        session = this.getSessionFromFolder(folder, sessionUuid);
      });
    }

    return session;
  }
}

export default new CourseManager(store);
