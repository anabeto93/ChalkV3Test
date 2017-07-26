import React, { Component } from "react";
import { connect } from "react-redux";
import { AppBar } from "material-ui";
import logoImage from "../assets/logo.png";

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
    const { course } = this.props;

    return (
      <AppBar title={Header.logo(course !== undefined && course !== null ? course.title : 'Chalkboard Education')}/>
    )
  }
}

function mapStateToProps({ routing: { course } }) {
  return { course };
}

export default connect(mapStateToProps)(Header);
