import React, { Component } from "react";
import { withRouter } from 'react-router-dom';
import { connect } from "react-redux";
import AppBar from "material-ui/AppBar";
import IconButton from "material-ui/IconButton";
import Back from "material-ui/svg-icons/navigation/chevron-left";

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
        <IconButton onClick={history.goBack}>
          <Back>Back</Back>
        </IconButton>
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
