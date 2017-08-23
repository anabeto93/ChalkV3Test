import _ from 'lodash';
import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { Link } from 'react-router-dom';

export class CoursesList extends Component {
  shouldComponentUpdate(nextProps) {
    if (!this.props.courses) {
      return true;
    }

    return !_.isEqual(this.props.courses, nextProps.courses);
  }

  render() {
    const { courses } = this.props;

    return (
      <List>
        {undefined !== courses &&
          courses.map(course => {
            return (
              <Link
                className="link-primary"
                key={course.uuid}
                to={`/courses/${course.uuid}/folders/list`}
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
