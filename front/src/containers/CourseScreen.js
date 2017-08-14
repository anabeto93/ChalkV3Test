import { connect } from 'react-redux';
import React, { Component } from 'react';

import CoursesList from '../components/Course/CoursesList';
import UserPanel from '../components/Course/UserPanel';

export class CourseScreen extends Component {
  render() {
    console.log('rendering CourseScreen');
    const { items } = this.props;

    return (
      <div>
        <UserPanel/>
        <CoursesList courses={items} />
      </div>
    );
  }
}

function mapStateToProps({ courses: { items } }) {
  return { items };
}

export default connect(mapStateToProps)(CourseScreen);
