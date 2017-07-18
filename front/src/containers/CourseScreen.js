import { connect } from 'react-redux';
import { Link } from 'react-router-dom';
import React, { Component } from 'react';

import { getCoursesInformations } from '../actions/actionCreators';
import CoursesList from '../components/Course/CoursesList';

export class CourseScreen extends Component {
  handleLoad = event => {
    event.preventDefault();
    this.props.dispatch(getCoursesInformations());
  };

  render() {
    console.log('rendering CourseScreen');
    const { isFetching, items } = this.props;

    return (
      <div>
        <h1>Courses</h1>
        <button onClick={this.handleLoad}>Load courses</button>
        <p>
          {isFetching ? 'Loading...' : ''}
        </p>
        <p>
          {items.length}
        </p>
        <CoursesList courses={items} />
        <Link to="/">Home</Link>
      </div>
    );
  }
}

function mapStateToProps({ courses: { isFetching, items } }) {
  return { isFetching, items };
}

export default connect(mapStateToProps)(CourseScreen);
