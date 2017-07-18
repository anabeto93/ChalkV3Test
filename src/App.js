import React, { Component } from 'react';
import { Provider } from 'react-redux';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import CourseScreen from './containers/CourseScreen';
import HomeScreen from './containers/HomeScreen';
import PrivateRoute from './containers/PrivateRoute';
import store from './store/store';

import './App.css';

class App extends Component {
  render() {
    return (
      <Provider store={store}>
        <Router>
          <div>
            <Route exact path="/" component={HomeScreen} />
            <PrivateRoute exact path="/course" component={CourseScreen} />
          </div>
        </Router>
      </Provider>
    );
  }
}

export default App;
