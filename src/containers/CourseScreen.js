import { connect } from 'react-redux';
import React, { Component } from 'react';

import CoursesList from '../components/Course/CoursesList';
import UserPanel from '../components/Course/UserPanel';

export class CourseScreen extends Component {
  render() {
    const { courses } = this.props;

    return (
      <div>
        <UserPanel />
        <CoursesList courses={courses} />
      </div>
    );
  }
}

function mapStateToProps({ content: { courses } }) {
  return { courses };
}

export default connect(mapStateToProps)(CourseScreen);
