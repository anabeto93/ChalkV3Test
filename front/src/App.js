import React, { Component } from 'react';
import { BrowserRouter as Router, Route } from 'react-router-dom';

import CourseScreen from './containers/CourseScreen'
import HomeScreen from './containers/HomeScreen'

import logo from './logo.svg';
import './App.css';

class App extends Component {
  render() {
    return (
      <Router>
        <div>
          <Route exact path='/' component={HomeScreen} />
          <Route exact path='/course' component={CourseScreen} />
        </div>
      </Router>
    );
  }
}

export default App;
