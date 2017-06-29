import React, { Component } from 'react'
import { Link } from 'react-router-dom'

export class CourseScreen extends Component {
  render () {
    console.log('rendering CourseScreen')

    return (
      <div>
        <h1>Course</h1>
        <Link to='/'>Home</Link>
      </div>
    )
  }
}

export default CourseScreen
