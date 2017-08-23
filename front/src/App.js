import { darkBlack } from 'material-ui/styles/colors';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import React, { Component } from 'react';

import { Provider } from 'react-redux';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';

import './App.css';
import logo from './assets/logo.png';
import Header from './components/Header';
import NotFound from './components/NotFound';
import Updates from './components/Updates/Updates';
import * as routes from './config/routes';

import CourseScreen from './containers/CourseScreen';
import FolderScreen from './containers/FolderScreen';
import HomeScreen from './containers/HomeScreen';
import LoginScreen from './containers/LoginScreen';
import PrivateRoute from './containers/PrivateRoute';
import SessionDetailScreen from './containers/SessionDetailScreen';
import SessionScreen from './containers/SessionScreen';
import store from './store/store';

const PRIMARY_COLOR = '#fc3691';

class App extends Component {
  render() {
    const logoApp = (
      <span>
        <img
          src={logo}
          alt="Chalkboard Education"
          style={{ float: 'left', maxHeight: '80%', margin: '6px' }}
        />{' '}
        Chalkboard Education
      </span>
    );

    return (
      <Provider store={store}>
        <MuiThemeProvider
          muiTheme={getMuiTheme({
            palette: {
              primary1Color: PRIMARY_COLOR,
              primary2Color: darkBlack,
              textColor1: darkBlack
            }
          })}
        >
          <Router>
            <div>
              <Header />
              <Updates />
              <Switch>
                <Route exact path={routes.HOME} component={HomeScreen} />
                <Route exact path={routes.LOGIN} component={LoginScreen} />
                <PrivateRoute
                  exact
                  path={routes.COURSES}
                  component={CourseScreen}
                />
                <PrivateRoute
                  exact
                  path={routes.FOLDER_LIST}
                  component={FolderScreen}
                />
                <PrivateRoute
                  exact
                  path={routes.SESSION_LIST}
                  component={SessionScreen}
                />
                <PrivateRoute
                  exact
                  path={routes.SESSION_LIST_WITHOUT_FOLDER}
                  component={SessionScreen}
                />
                <PrivateRoute
                  exact
                  path={routes.SESSION_DETAIL}
                  component={SessionDetailScreen}
                />
                <Route component={NotFound} />
              </Switch>
            </div>
          </Router>
        </MuiThemeProvider>
      </Provider>
    );
  }
}

export default App;
