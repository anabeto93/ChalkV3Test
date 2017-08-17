import I18n from 'i18n-js';
import { IconButton, Subheader } from 'material-ui';
import { IconMenu, MenuItem } from 'material-ui/IconMenu';
import { List, ListItem } from 'material-ui/List';
import { grey400 } from 'material-ui/styles/colors';
import MoreVertIcon from 'material-ui/svg-icons/navigation/more-vert';
import React, { Component } from 'react';
import UserPanel from '../components/Course/UserPanel';
import { availableLocales } from '../config/translations';

class AccountScreen extends Component {
  editLocale = (event, item) => {
    I18n.locale = item.key;
  };

  rightIconMenuLocale = () => {
    const iconButtonElement = (
      <IconButton touch={true} tooltip="Edit" tooltipPosition="bottom-left">
        <MoreVertIcon color={grey400} />
      </IconButton>
    );

    let items = [];

    for (let locale in availableLocales) {
      if (availableLocales.hasOwnProperty(locale)) {
        items.push(
          <MenuItem key={locale}>
            {availableLocales[locale]}
          </MenuItem>
        );
      }
    }

    return (
      <IconMenu
        onItemTouchTap={this.editLocale}
        iconButtonElement={iconButtonElement}
      >
        {items}
      </IconMenu>
    );
  };

  render() {
    return (
      <div>
        <UserPanel />
        <List>
          <Subheader>Settings</Subheader>
          <ListItem rightIconButton={this.rightIconMenuLocale()}>
            {I18n.t('account.language')} :{' '}
            {availableLocales[I18n.currentLocale()]}
          </ListItem>
        </List>
      </div>
    );
  }
}

export default AccountScreen;
