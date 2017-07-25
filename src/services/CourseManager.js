import store from "../store/store";

class CourseManager {
  constructor(store) {
    this.store = store;
  }

  /**
   * @param {string} uuid
   * @return {Object}
   */
  getCourse(uuid) {
    let state = this.store.getState();
    if (state.hasOwnProperty('courses')) {
      return state.courses.items.find((course) => course.uuid === uuid);
    }
  }
}

export default new CourseManager(store);
