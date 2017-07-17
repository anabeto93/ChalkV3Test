import React, { Component } from 'react'

export class CoursesList extends Component {
  shouldComponentUpdate(nextProps) {
    if (!this.props.data || !this.props.data.courses) {
      return true;
    }

    if (!_.isEqual(this.props.courses, nextProps.courses)) {
      console.log('Please render CoursesList')
      return true;
    }

    console.log('No render CoursesList')

    return false;
  }

  render () {
    const { data } = this.props

    console.log('rendering CoursesList')

    return (
        <ul>
        { undefined !== courses && courses.map((course) => {
            return (
              <li key={course.uuid}>
                <h1>{course.title}</h1>
                <p>{course.teacherName}</p>
              </li>
            )
          }
        ) }
        </ul>
    )
  }
}

export default CoursesList
