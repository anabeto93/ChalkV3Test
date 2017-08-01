import React, { Component } from "react";
import { Link, withRouter } from 'react-router-dom';
import { connect } from "react-redux";
import { AppBar } from "material-ui";

import logoImage from "../assets/logo.png";
import RouteResolver from "../services/RouteResolver";

class Header extends Component {
  logo() {
    const { title } = this.props;

    return (
      <span>
        <img
          src={logoImage}
          alt="Chalkboard Education"
          style={{ float: 'left', maxHeight: '80%', margin: '6px' }}
        />{' '}
        {title}
      </span>
    );
  }

  leftIcon() {
    const { location, history } = this.props;
    if (location.pathname !== '/') {
      return (
        <button onClick={history.goBack}>Back</button>
      )
    }
  }

  render() {
    return (
      <AppBar iconElementLeft={this.leftIcon()} title={this.logo()}/>
    )
  }
}

function mapStateToProps(state, props) {
  let route = RouteResolver.resolve(props.location);
  let title = RouteResolver.resolveTitle(route);

  return { title };
}

export default withRouter(connect(mapStateToProps)(Header));
