import { connect } from 'react-redux';
import React, { Component } from 'react';

import CoursesList from '../components/Course/CoursesList';

export class CourseScreen extends Component {
  render() {
    console.log('rendering CourseScreen');
    const { items } = this.props;

    return (
      <div>
        <CoursesList courses={items} />
      </div>
    );
  }
}

function mapStateToProps({ courses: { items } }) {
  return { items };
}

export default connect(mapStateToProps)(CourseScreen);
