import { darkBlack } from 'material-ui/styles/colors';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import React, { Component } from 'react';
import { Provider } from 'react-redux';
import { HashRouter, Route, Switch } from 'react-router-dom';
import { persistStore } from 'redux-persist';

import Header from './components/Header';
import NotFound from './components/NotFound';
import Updates from './components/Updates/Updates';
import * as routes from './config/routes';
import AccountScreen from './containers/AccountScreen';
import CourseScreen from './containers/CourseScreen';
import FolderScreen from './containers/FolderScreen';
import HomeScreen from './containers/HomeScreen';
import LoginScreen from './containers/LoginScreen';
import PrivateRoute from './containers/PrivateRoute';
import ScrollToTop from './components/Utils/ScrollToTop';
import SendScreen from './containers/SendScreen';
import SessionDetailScreen from './containers/SessionDetailScreen';
import SessionScreen from './containers/SessionScreen';
import QuestionDetailScreen from './containers/QuestionDetailScreen';
import SendSMSScreen from './containers/SendSMSScreen';
import ValidateSessionByCodeScreen from './containers/ValidateSessionByCodeScreen';
import Logout from './components/Logout';

// Check network status
import networkStatusEventListener from './services/network/networkStatusEventListener';
import clock from './services/updates/clock';
import store from './store/store';
import { reInitContentStates, reinitUpdates } from './actions/actionCreators';

const PRIMARY_COLOR = '#d8497d';

class App extends Component {
  constructor() {
    super();
    this.state = { rehydrated: false };
  }

  componentWillMount() {
    persistStore(store, {}, () => {
      this.setState({ rehydrated: true });
      store.dispatch(reinitUpdates());
      store.dispatch(reInitContentStates());
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
          <HashRouter>
            <ScrollToTop>
              <div>
                <Header />
                <div className="container-layout">
                  <Updates />
                  <Logout />
                  <div className="content-layout">
                    <Switch>
                      <Route exact path={routes.HOME} component={HomeScreen} />
                      <Route
                        exact
                        path={routes.LOGIN}
                        component={LoginScreen}
                      />
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
                        path={routes.QUESTION_DETAIL}
                        component={QuestionDetailScreen}
                      />
                      <PrivateRoute
                        exact
                        path={routes.SESSION_SEND}
                        component={SendScreen}
                      />
                      <PrivateRoute
                        exact
                        path={routes.SESSION_SEND_SMS}
                        component={SendSMSScreen}
                      />
                      <PrivateRoute
                        path={routes.ACCOUNT}
                        component={AccountScreen}
                      />
                      <PrivateRoute
                        exact
                        path={routes.SESSION_VALIDATE_SMS}
                        component={ValidateSessionByCodeScreen}
                      />
                      <Route component={NotFound} />
                    </Switch>
                  </div>
                </div>
              </div>
            </ScrollToTop>
          </HashRouter>
        </MuiThemeProvider>
      </Provider>
    );
  }
}

export default App;
