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
      let courses = state.courses;
      let foundedCourse = null;
      courses.items.forEach((course) => {
        if (course.uuid === uuid) {
          foundedCourse = course;
          return true;
        }
      });

      return foundedCourse;
    }
  }
}

export default new CourseManager(store);
