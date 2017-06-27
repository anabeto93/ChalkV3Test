import React, { Component } from 'react'
import { Link } from 'react-router-dom'

export class HomeScreen extends Component {
  render () {
    return (
      <div>
        <h1>Welcome!</h1>
        <p>This is init</p>
        <Link to='/course'>Course</Link>
      </div>
    )
  }
}

export default HomeScreen
