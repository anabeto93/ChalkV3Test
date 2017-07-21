import { connect } from 'react-redux';
import { Link } from 'react-router-dom';
import { List, ListItem } from 'material-ui/List';
import React, { Component } from 'react';

export class FolderScreen extends Component {
  render() {
    const { items } = this.props;

    return (
      <div>
        <h1>Folders</h1>
        <Link to="/">Home</Link>
      </div>
    );
  }
}

function mapStateToProps({ courses: { items } }) {
  return { items };
}

export default connect(mapStateToProps)(FolderScreen);
