import AppBar from 'material-ui/AppBar';
import IconButton from 'material-ui/IconButton';
import Back from 'material-ui/svg-icons/navigation/chevron-left';
import UserIcon from 'material-ui/svg-icons/social/person';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';

import logoImage from '../assets/logo.png';
import { COURSES, HOME } from '../config/routes';
import RouteResolver from '../services/RouteResolver';

class Header extends Component {
  handleRedirectCourseList = () => {
    this.props.history.push(COURSES);
  };

  handleGoBack = () => {
    if (this.props.location.pathname !== COURSES) {
      this.props.history.goBack();
    }
  };

  logo = () => {
    const { title } = this.props;

    return (
      <span style={{ fontSize: '14px' }}>
        <img
          src={logoImage}
          alt="Chalkboard Education"
          style={{
            paddingTop: '10px',
            float: 'left',
            maxHeight: '50%',
            margin: '6px'
          }}
        />{' '}
        {title}
      </span>
    );
  };

  leftIcon = () => {
    return (
      <IconButton onClick={this.handleGoBack}>
        <Back>Back</Back>
      </IconButton>
    );
  };

  showMenuIconButton = () => {
    const { location } = this.props;

    return (
      location.pathname !== HOME &&
      location.pathname !== COURSES &&
      RouteResolver.resolve(location) !== undefined
    );
  };

  rightIcon = () => {
    return (
      <IconButton onClick={this.handleRedirectCourseList}>
        <UserIcon />
      </IconButton>
    );
  };

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
  let title = '';
  let route = RouteResolver.resolve(props.location);

  if (route !== undefined) {
    title = RouteResolver.resolveTitle(route);
  }

  return { title };
}

export default withRouter(connect(mapStateToProps)(Header));
