import { List, ListItem } from 'material-ui/List';
import React, { Component } from 'react';
import UserPanel from '../components/Course/UserPanel';

class AccountScreen extends Component {
  render() {
    return (
      <div>
        <UserPanel />
        <List>
          <ListItem>Langues</ListItem>
        </List>
      </div>
    );
  }
}

export default AccountScreen;
