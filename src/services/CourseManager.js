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
}

export default new CourseManager(store);
