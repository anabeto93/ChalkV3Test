import I18n from 'i18n-js';
import {
  IconButton,
  Button,
  List,
  ListItem,
  ListItemText,
  ListSubheader,
  ListItemSecondaryAction,
  Menu,
  MenuItem,
  Tooltip
} from '@material-ui/core';
import { grey400 } from '@material-ui/core/colors';
import MoreVert from '@material-ui/icons/MoreVert';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import {
  getUpdates,
  setLocale,
  requestUserLogout
} from '../actions/actionCreators';
import UserPanel from '../components/Course/UserPanel';
import { availableLocales } from '../config/translations';

class AccountScreen extends Component {
  state = {
    anchorEl: null
  };

  handleMenuOpen = e => {
    this.setState({ anchorEl: e.currentTarget });
  };

  handleMenuClose = () => {
    this.setState({ anchorEl: null });
  };
  /**
   * @param {Event} event
   * @param {string} item
   */
  editLocale = locale => {
    this.props.dispatch(setLocale(locale));

    this.handleMenuClose();
  };

  rightIconMenuLocale = () => {
    const { anchorEl } = this.state;
    const { settings } = this.props;

    let items = [];

    for (let locale in availableLocales) {
      if (availableLocales.hasOwnProperty(locale)) {
        items.push(
          <MenuItem
            key={locale}
            selected={locale === settings.locale}
            onClick={() => this.editLocale(locale)}
          >
            <ListItemText primary={availableLocales[locale]} />
          </MenuItem>
        );
      }
    }

    return (
      <React.Fragment>
        <Tooltip title={I18n.t('update.edit', { locale: settings.locale })}>
          <IconButton onClick={this.handleMenuOpen}>
            <MoreVert color={grey400} />
          </IconButton>
        </Tooltip>
        <Menu
          anchorEl={anchorEl}
          open={Boolean(anchorEl)}
          onClose={this.handleMenuClose}
        >
          {items}
        </Menu>
      </React.Fragment>
    );
  };

  handleUpdate = () => {
    this.props.dispatch(getUpdates(this.props.updatedAt));
  };

  handleLogout = () => {
    this.props.dispatch(requestUserLogout());
  };

  render() {
    const { settings } = this.props;

    const updateButton =
      !this.props.hasUpdates &&
      !this.props.isFetchingContent &&
      !this.props.isFetchingUpdates &&
      <div style={{ textAlign: 'center' }}>
        <Button
          variant="raised"
          onClick={this.handleUpdate}
          color="primary"
          style={{ margin: '10px' }}
        >
          {I18n.t('update.checkForUpdates', { locale: settings.locale })}
        </Button>
      </div>;

    return (
      <div>
        <UserPanel />
        {updateButton}
        <List>
          <ListSubheader>
            {I18n.t('account.settings.label', { locale: settings.locale })}
          </ListSubheader>

          <ListItem>
            <ListItemText
              primary={
                I18n.t('account.language', { locale: settings.locale }) +
                ' : ' +
                availableLocales[settings.locale]
              }
            />

            <ListItemSecondaryAction>
              {this.rightIconMenuLocale()}
            </ListItemSecondaryAction>
          </ListItem>
        </List>

        <div style={{ textAlign: 'center' }}>
          <Button
            variant="raised"
            color="primary"
            onClick={this.handleLogout}
            style={{ margin: '10px' }}
          >
            {I18n.t('logout.button', { locale: settings.locale })}
          </Button>
        </div>
      </div>
    );
  }
}

/**
 * @param {object} content
 * @param {object} settings
 * @param {object} updates
 * @returns {{settings: *, hasUpdates: boolean, isFetchingContent: boolean, isFetchingUpdates: boolean}}
 */
function mapStateToProps({ content, settings, updates }) {
  return {
    settings,
    hasUpdates: updates.hasUpdates,
    isFetchingContent: content.isFetching,
    isFetchingUpdates: updates.isFetching,
    updatedAt: content.updatedAt
  };
}

export default connect(mapStateToProps)(AccountScreen);
