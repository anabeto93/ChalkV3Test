import React, { Component } from 'react';
import { BottomNavigation, BottomNavigationAction } from '@material-ui/core';
import { Home, Settings } from '@material-ui/icons';
import { withRouter } from 'react-router-dom';
import { HOME, ACCOUNT, COURSES, LOGIN } from '../config/routes';
import RouteResolver from '../services/RouteResolver';

class Footer extends Component {
  handleHome = () => {
    this.props.history.push(COURSES);
  };

  handleSettings = () => {
    this.props.history.push(ACCOUNT);
  };

  render() {
    const { pathname } = this.props.location;
    const matchPath = RouteResolver.resolve(this.props.location);

    if (
      pathname === HOME ||
      matchPath === undefined ||
      matchPath.path === LOGIN
    ) {
      return <div />;
    }

    return (
      <BottomNavigation className="bottom-nav">
        <BottomNavigationAction icon={<Home />} onClick={this.handleHome} />
        <BottomNavigationAction
          icon={<Settings />}
          onClick={this.handleSettings}
        />
      </BottomNavigation>
    );
  }
}

export default withRouter(Footer);
