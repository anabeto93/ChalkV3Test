import { connect } from 'react-redux'
import { Link } from 'react-router-dom'
import React, { Component } from 'react'

import LoginScreen from './LoginScreen'

export class HomeScreen extends Component {
  render () {
    console.log('rendering HomeScreen')

    return this.props.loggedIn
      ? (
        <div>
          <h1>Welcome!</h1>
          <p>This is init!!!</p>
          <Link to='/course'>Course</Link>
        </div>
      )
      : <LoginScreen />
  }
}

function mapStateToProps ({ currentUser: { loginState } }) {
  return { loggedIn: loginState === 'success' }
}

export default connect(mapStateToProps)(HomeScreen)
