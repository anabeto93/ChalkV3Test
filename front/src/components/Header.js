import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';
import { connect } from 'react-redux';
import AppBar from 'material-ui/AppBar';
import IconButton from 'material-ui/IconButton';
import Back from 'material-ui/svg-icons/navigation/chevron-left';
import UserIcon from 'material-ui/svg-icons/social/person';

import logoImage from '../assets/logo.png';
import RouteResolver from '../services/RouteResolver';
import { COURSES } from '../config/routes';

class Header extends Component {
  courseList() {
    this.props.history.push(COURSES);
  }

  logo() {
    const { title } = this.props;

    return (
      <span style={{ fontSize: '14px' }}>
        <img
          src={logoImage}
          alt="Chalkboard Education"
          style={{ paddingTop: '10px', float: 'left', maxHeight: '50%', margin: '6px' }}
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
      );
    }
  }

  showMenuIconButton() {
    const { location } = this.props;

    return location.pathname !== '/';
  }

  rightIcon() {
    return (
      <IconButton onClick={this.courseList.bind(this)}>
        <UserIcon />
      </IconButton>
    );
  }

  render() {
    return (
      <AppBar
        title={this.logo()}
        iconElementLeft={this.leftIcon()}
        iconElementRight={this.rightIcon()}
        showMenuIconButton={this.showMenuIconButton()}
      />
    );
  }
}

function mapStateToProps(state, props) {
  let route = RouteResolver.resolve(props.location);
  let title = RouteResolver.resolveTitle(route);

  return { title };
}

export default withRouter(connect(mapStateToProps)(Header));
