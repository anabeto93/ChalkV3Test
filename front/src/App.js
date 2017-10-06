import { darkBlack } from 'material-ui/styles/colors';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import React, { Component } from 'react';
import { persistStore } from 'redux-persist';
import { Provider } from 'react-redux';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';

import './App.css';
import Header from './components/Header';
import NotFound from './components/NotFound';
import Updates from './components/Updates/Updates';
import * as routes from './config/routes';
import AccountScreen from './containers/AccountScreen';
import ValidateSession from './services/ValidateSession';

import CourseScreen from './containers/CourseScreen';
import FolderScreen from './containers/FolderScreen';
import HomeScreen from './containers/HomeScreen';
import LoginScreen from './containers/LoginScreen';
import PrivateRoute from './containers/PrivateRoute';
import SendScreen from './containers/SendScreen';
import SessionDetailScreen from './containers/SessionDetailScreen';
import SessionScreen from './containers/SessionScreen';
import store from './store/store';

// Check network status
import networkStatusEventListener from './services/network/networkStatusEventListener';
import clock from './services/updates/clock';

const PRIMARY_COLOR = '#d8497d';

class App extends Component {
  constructor() {
    super();
    this.state = { rehydrated: false };
  }

  componentWillMount() {
    persistStore(store, {}, () => {
      this.setState({ rehydrated: true });
      networkStatusEventListener();
      clock();
    });
  }

  render() {
    if (!this.state.rehydrated) {
      return <div>Loading...</div>;
    }

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
                <PrivateRoute
                  exact
                  path={routes.SESSION_SEND}
                  component={SendScreen}
                />
                <PrivateRoute path={routes.ACCOUNT} component={AccountScreen} />
                <PrivateRoute
                  exact
                  path={routes.SESSION_VALIDATE_SMS}
                  component={ValidateSession}
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
