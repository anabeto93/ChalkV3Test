import React, { Component } from "react";
import { withRouter } from 'react-router-dom';
import { connect } from "react-redux";
import { AppBar } from "material-ui";

import logoImage from "../assets/logo.png";
import RouteResolver from "../services/RouteResolver";

class Header extends Component {
  /**
   * @param {string} title
   */
  static logo(title = 'Chalkboard Education') {
    return (
      <span>
        <img
          src={logoImage}
          alt="Chalkboard Education"
          style={{ float: 'left', maxHeight: '80%', margin: '6px' }}
        />{' '}
        { title }
      </span>
    );
  }

  render() {
    const { title } = this.props;

    return (
      <AppBar title={Header.logo(title !== null ? title : 'Chalkboard Education')}/>
    )
  }
}

function mapStateToProps(state, props) {
    let route = RouteResolver.resolve(props.location);
    let title = RouteResolver.resolveTitle(route);

    return { title };
}

export default withRouter(connect(mapStateToProps)(Header));
