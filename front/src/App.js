import React, { Component } from 'react';
import { Provider } from 'react-redux'
import { BrowserRouter as Router, Route } from 'react-router-dom';

import CourseScreen from './containers/CourseScreen'
import HomeScreenWithData from './containers/HomeScreen'
import store from './store/store'

import './App.css';

class App extends Component {
  render() {
    return (
      <Provider store={store}>
        <Router>
          <div>
            <Route exact path='/' component={HomeScreenWithData} />
            <Route exact path='/course' component={CourseScreen} />
          </div>
        </Router>
      </Provider>
    );
  }
}

export default App;
