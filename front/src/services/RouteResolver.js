import { matchPath } from 'react-router-dom';

import CourseManager from './CourseManager';
import * as routes from '../config/routes';

export default {
  /**
   * @param {string} pathname from Object
   * @returns {Object|undefined}
   */
  resolve({ pathname }) {
    return Object.values(routes)
      .map(path => {
        return matchPath(pathname, { path, exact: true });
      })
      .find(match => match !== null);
  },

  /**
   * @param {string} path from Object
   * @param {Object} params from Object
   * @returns {string}
   */
  resolveTitle({ path, params }) {
    let course = undefined;
    let folder = undefined;

    switch (path) {
      case routes.COURSES:
        return 'Chalkboard Education';
      case routes.FOLDER_LIST:
        course = CourseManager.getCourse(params.courseId);
        return course ? course.title : '';
      case routes.SESSION_LIST:
        course = CourseManager.getCourse(params.courseId);

        if (course !== undefined) {
          folder = CourseManager.getFolderFromCourse(course, params.folderId);
          return folder ? folder.title : '';
        }

        return course ? course.title : '';
      case routes.SESSION_DETAIL:
        course = CourseManager.getCourse(params.courseId);

        return course ? course.title : '';
      default:
        return 'Chalkboard Education';
    }
  }
};
