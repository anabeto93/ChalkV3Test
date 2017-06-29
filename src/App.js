import React, { Component } from 'react';

import { BrowserRouter as Router, Route } from 'react-router-dom';

import CourseScreen from './containers/CourseScreen'
import HomeScreenWithData from './containers/HomeScreen'

import './App.css';

class App extends Component {
  render() {
    return (
      <Router>
        <div>
          <Route exact path='/' component={HomeScreenWithData} />
          <Route exact path='/course' component={CourseScreen} />
        </div>
      </Router>
    );
  }
}

export default App;
