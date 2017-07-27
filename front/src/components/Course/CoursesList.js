import _ from 'lodash';
import { List, ListItem } from 'material-ui/List';
import React, { Component } from 'react';

export class CoursesList extends Component {
  shouldComponentUpdate(nextProps) {
    if (!this.props.courses) {
      return true;
    }

    if (!_.isEqual(this.props.courses, nextProps.courses)) {
      return true;
    }

    return false;
  }

  render() {
    const { courses } = this.props;

    console.log('rendering CoursesList');

    return (
      <List>
        {undefined !== courses &&
          courses.map(course => {
            return (
              <ListItem
                key={course.uuid}
                primaryText={course.title}
                secondaryText={course.teacherName}
              />
            );
          })}
      </List>
    );
  }
}

export default CoursesList;
