import React, { Component } from "react";
import { AppBar } from "material-ui";
import logoImage from "../assets/logo.png";

export default class Header extends Component {
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

    return (
      <AppBar title={Header.logo()}/>
    )
  }
}
