import I18n from 'i18n-js';
import { List, ListItem, ListItemText, Divider } from '@material-ui/core';
import Arrow from '@material-ui/icons/KeyboardArrowRight';
import React, { Component } from 'react';
import { Link } from 'react-router-dom';

import generateUrl from '../../services/generateUrl';
import { FOLDER_LIST } from '../../config/routes';

export class CoursesList extends Component {
  render() {
    const { courses, locale } = this.props;
    const totalCourses = Object.keys(courses).length;

    if (undefined === courses || 0 === totalCourses) {
      return (
        <p className="screen-centered alert">
          {I18n.t('course.noContentAvailable', { locale })}
        </p>
      );
    }

    return (
      <List>
        {Object.keys(courses).map((key, index) => {
          let course = courses[key];

          return (
            <Link
              className="link-primary"
              key={course.uuid}
              to={generateUrl(FOLDER_LIST, { ':courseUuid': course.uuid })}
            >
              <ListItem button>
                <ListItemText
                  primary={course.title}
                  secondary={course.teacherName}
                />
                <Arrow />
              </ListItem>
              {index < totalCourses - 1 && <Divider />}
            </Link>
          );
        })}
      </List>
    );
  }
}

export default CoursesList;
