import { matchPath } from 'react-router-dom';

import CourseManager from './CourseManager';
import getConfig from '../config/index';
import * as routes from '../config/routes';
import store from '../store/store';

const APP_NAME = getConfig().appName;

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
        return APP_NAME;
      case routes.FOLDER_LIST:
        course = CourseManager.getCourse(
          store.getState().content.courses,
          params.courseUuid
        );
        return course ? course.title : '';
      case routes.SESSION_LIST:
        const { courses, folders } = store.getState().content;

        course = CourseManager.getCourse(courses, params.courseUuid);

        if (course !== undefined) {
          folder = CourseManager.getFolder(folders, params.folderUuid);

          return folder ? folder.title : '';
        }

        return course ? course.title : '';
      case routes.SESSION_DETAIL:
        course = CourseManager.getCourse(
          store.getState().content.courses,
          params.courseUuid
        );

        return course ? course.title : '';
      default:
        return APP_NAME;
    }
  }
};
