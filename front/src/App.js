import AppBar from 'material-ui/AppBar';
import React, { Component } from 'react';
import getMuiTheme from 'material-ui/styles/getMuiTheme';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';

import { Provider } from 'react-redux';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import CourseScreen from './containers/CourseScreen';
import HomeScreen from './containers/HomeScreen';
import PrivateRoute from './containers/PrivateRoute';
import store from './store/store';
import Updates from './components/Updates/Updates';

import './App.css';
import logo from './assets/logo.png';

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
              textColor: PRIMARY_COLOR
            }
          })}
        >
          <Router>
            <div>
              <AppBar title={logoApp} />
              <Updates />
              <Route exact path="/" component={HomeScreen} />
              <PrivateRoute exact path="/course" component={CourseScreen} />
            </div>
          </Router>
        </MuiThemeProvider>
      </Provider>
    );
  }
}

export default App;
