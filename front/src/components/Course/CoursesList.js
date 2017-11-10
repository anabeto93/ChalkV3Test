import I18n from 'i18n-js';
import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { Link } from 'react-router-dom';

import generateUrl from '../../services/generateUrl';
import { FOLDER_LIST } from '../../config/routes';

export class CoursesList extends Component {
  render() {
    const { courses, locale } = this.props;

    if (undefined === courses || 0 === Object.keys(courses).length) {
      return (
        <p className="screen-centered alert">
          {I18n.t('course.noContentAvailable', { locale })}
        </p>
      );
    }

    return (
      <List>
        {Object.keys(courses).map(key => {
          let course = courses[key];

          return (
            <Link
              className="link-primary"
              key={course.uuid}
              to={generateUrl(FOLDER_LIST, { ':courseUuid': course.uuid })}
            >
              <ListItem
                primaryText={course.title}
                secondaryText={course.teacherName}
                rightIcon={<Arrow />}
              />
            </Link>
          );
        })}
      </List>
    );
  }
}

export default CoursesList;
