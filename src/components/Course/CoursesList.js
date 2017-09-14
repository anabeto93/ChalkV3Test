import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import generateUrl from '../../services/generateUrl';
import { FOLDER_LIST } from '../../config/routes';

export class CoursesList extends Component {
  render() {
    const { courses } = this.props;

    return (
      <List>
        {undefined !== courses &&
          Object.keys(courses).map(key => {
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
