import _ from 'lodash';
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
      <ul>
        {undefined !== courses &&
          courses.map(course => {
            return (
              <li key={course.uuid}>
                <h1>
                  {course.title}
                </h1>
                <p>
                  {course.teacherName}
                </p>
              </li>
            );
          })}
      </ul>
    );
  }
}

export default CoursesList;
