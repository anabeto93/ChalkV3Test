import {
  AppBar,
  Toolbar,
  IconButton,
  Typography,
  Avatar
} from '@material-ui/core';
import BackIcon from '@material-ui/icons/ChevronLeft';
import NextIcon from '@material-ui/icons/ChevronRight';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';

import logoImage from '../assets/logo.png';
import getConfig from '../config/index';
import {
  COURSES,
  HOME,
  SESSION_LIST,
  FOLDER_LIST,
  SESSION_DETAIL
} from '../config/routes';
import RouteResolver from '../services/RouteResolver';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';
import sessionNext from '../services/session/sessionNext';

const APP_NAME = getConfig().appName;

class Header extends Component {
  handleRedirectToList = () => {
    const { location, history } = this.props;
    const matchPath = RouteResolver.resolve(location);

    if (matchPath.path === SESSION_DETAIL) {
      const sessionUuid = matchPath.params.sessionUuid;

      const { sessions } = this.props;

      const session = CourseManager.getSession(sessions, sessionUuid);

      if (session) {
        return history.push(
          generateUrl(SESSION_LIST, {
            ':courseUuid': session.courseUuid,
            ':folderUuid': session.folderUuid
          })
        );
      }
    }

    if (matchPath.path === SESSION_LIST) {
      const { folders } = this.props;
      const courseUuid = folders[matchPath.params.folderUuid].courseUuid;

      const courseFolders = CourseManager.getFoldersFromCourse(
        folders,
        courseUuid
      );

      const totalFolders = Object.keys(courseFolders).length;
      const firstFolder = folders[Object.keys(courseFolders)[0]];

      if (!(totalFolders === 1 && firstFolder.isDefault)) {
        return history.push(
          generateUrl(FOLDER_LIST, {
            ':courseUuid': courseUuid
          })
        );
      }
    }

    return history.push(COURSES);
  };

  handleGoNext = () => {
    const { location, sessions } = this.props;
    const matchPath = RouteResolver.resolve(location);
    const { courseUuid } = matchPath.params;

    const session = CourseManager.getSession(
      sessions,
      matchPath.params.sessionUuid
    );

    sessionNext({ ...this.props, session, courseUuid });
  };

  handleGoBack = () => {
    if (this.props.location.pathname !== COURSES) {
      this.props.history.goBack();
    }
  };

  logo = () => {
    const { title } = this.props;

    return (
      <React.Fragment>
        <Avatar
          src={logoImage}
          alt={APP_NAME}
          style={{
            padding: '0.5em'
          }}
          onClick={this.handleRedirectToList}
        />
        <Typography
          color="inherit"
          onClick={this.handleRedirectToList}
          aria-label="Top"
          style={{ flex: 1 }}
        >
          {title}
        </Typography>
      </React.Fragment>
    );
  };

  leftIcon = () => {
    return (
      <IconButton color="inherit" onClick={this.handleGoBack} aria-label="Back">
        <BackIcon />
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
    const matchPath = RouteResolver.resolve(this.props.location);

    // show next button on session detail screen only
    if (matchPath.path === SESSION_DETAIL) {
      return (
        <IconButton color="inherit" onClick={this.handleGoNext}>
          <NextIcon />
        </IconButton>
      );
    }
  };

  render() {
    return (
      <AppBar position="fixed">
        <Toolbar>
          {this.showMenuIconButton() && this.leftIcon()}

          {this.logo()}

          {this.rightIcon()}
        </Toolbar>
      </AppBar>
    );
  }
}

/**
 * @param {Object} state
 * @param {Object} props
 * @returns {{title: string}}
 */
function mapStateToProps(state, props) {
  let title = '';
  const route = RouteResolver.resolve(props.location);

  if (route !== undefined) {
    title = RouteResolver.resolveTitle(route);
  }

  return {
    title,
    sessions: state.content.sessions,
    folders: state.content.folders
  };
}

export default withRouter(connect(mapStateToProps)(Header));
