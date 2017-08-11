import React, { Component } from 'react';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import { Provider } from 'react-redux';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import CourseScreen from './containers/CourseScreen';
import HomeScreen from './containers/HomeScreen';
import FolderScreen from './containers/FolderScreen';
import SessionScreen from './containers/SessionScreen';
import SessionDetailScreen from './containers/SessionDetailScreen';
import LoginScreen from './containers/LoginScreen';
import PrivateRoute from './containers/PrivateRoute';
import store from './store/store';
import Header from './components/Header';
import Updates from './components/Updates/Updates';
import * as routes from './config/routes';
import './App.css';

const PRIMARY_COLOR = '#fc3691';

class App extends Component {
  render() {
    return (
      <Provider store={store}>
        <MuiThemeProvider
          muiTheme={getMuiTheme({
            palette: {
              primary1Color: PRIMARY_COLOR,
              textColor: PRIMARY_COLOR
            }
          })}
        >
          <Router>
            <div>
              <Header />
              {/*<Updates />*/}
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
            </div>
          </Router>
        </MuiThemeProvider>
      </Provider>
    );
  }
}

export default App;
