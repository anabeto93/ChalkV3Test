import I18n from 'i18n-js';
import { IconButton, Subheader } from 'material-ui';
import { IconMenu, MenuItem } from 'material-ui/IconMenu';
import { List, ListItem } from 'material-ui/List';
import { grey400 } from 'material-ui/styles/colors';
import MoreVertIcon from 'material-ui/svg-icons/navigation/more-vert';
import RaisedButton from 'material-ui/RaisedButton';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { getUpdates, setLocale } from '../actions/actionCreators';
import UserPanel from '../components/Course/UserPanel';
import { availableLocales } from '../config/translations';

class AccountScreen extends Component {
  /**
   * @param {Event} event
   * @param {string} item
   */
  editLocale = (event, item) => {
    this.props.dispatch(setLocale(item.key));
  };

  rightIconMenuLocale = () => {
    const iconButtonElement = (
      <IconButton tooltip="Edit" tooltipPosition="bottom-left">
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

  handleUpdate = () => {
    this.props.dispatch(getUpdates());
  };

  render() {
    const { settings } = this.props;

    const updateButton =
      !this.props.hasUpdates &&
      !this.props.isFetchingContent &&
      !this.props.isFetchingUpdates &&
      <div style={{ textAlign: 'center' }}>
        <RaisedButton
          label={I18n.t('update.label', { locale: settings.locale })}
          onClick={this.handleUpdate}
          primary={true}
          style={{ margin: '10px' }}
        />
      </div>;

    return (
      <div>
        <UserPanel />
        {updateButton}
        <List>
          <Subheader>
            {I18n.t('account.settings.label', { locale: settings.locale })}
          </Subheader>
          <ListItem
            disabled={true}
            rightIconButton={this.rightIconMenuLocale()}
          >
            {I18n.t('account.language', { locale: settings.locale })} :{' '}
            {availableLocales[settings.locale]}
          </ListItem>
        </List>
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
    isFetchingUpdates: updates.isFetching
  };
}

export default connect(mapStateToProps)(AccountScreen);
