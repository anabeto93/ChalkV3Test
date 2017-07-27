import { connect } from 'react-redux';
import { Link } from 'react-router-dom';
import React, { Component } from 'react';

import CoursesList from '../components/Course/CoursesList';

export class CourseScreen extends Component {
  render() {
    console.log('rendering CourseScreen');
    const { items } = this.props;

    return (
      <div>
        <h1>Courses</h1>
        <CoursesList courses={items} />
        <Link to="/">Home</Link>
      </div>
    );
  }
}

function mapStateToProps({ courses: { items } }) {
  return { items };
}

export default connect(mapStateToProps)(CourseScreen);
