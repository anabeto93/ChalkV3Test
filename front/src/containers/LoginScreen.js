import React, { Component } from 'react'
import { connect } from 'react-redux'

import { login } from '../actions/actionCreators'

export class LoginScreen extends Component {
  login = (event) => {
    event.preventDefault()
    this.props.dispatch(login())
  }

  render () {
    console.log('rendering LoginScreen')

    return (
      <div>
        <form onSubmit={this.login}>
          <button type="submit">Login</button>
        </form>
      </div>
    )
  }
}

const mapStateToProps = ({ currentUser: { loginState } }) => ({ loginState })

export default connect(mapStateToProps)(LoginScreen)
