import I18n from 'i18n-js';
import RaisedButton from 'material-ui/RaisedButton';
import React, { Component } from 'react';
import { connect } from 'react-redux';

import { getCoursesInformations } from '../../actions/actionCreators';

const DEFAULT_STATE = { alreadyUpToDate: false, updating: false };
const ALREADY_UP_TO_DATE_MESSAGE_DELAY_IN_SECONDS = 5;

class Updates extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentWillReceiveProps(nextProps) {
    const { isFetching } = this.props.updates;

    const alreadyUpToDate =
      isFetching && !nextProps.isFetching && !nextProps.hasUpdates;

    this.setState({ ...DEFAULT_STATE, alreadyUpToDate: alreadyUpToDate });

    if (alreadyUpToDate) {
      setTimeout(
        () => this.setState({ ...DEFAULT_STATE, alreadyUpToDate: false }),
        ALREADY_UP_TO_DATE_MESSAGE_DELAY_IN_SECONDS * 1000
      );
    }
  }

  handleLoad = event => {
    event.preventDefault();
    this.props.dispatch(getCoursesInformations());
  };

  render() {
    const { updates, courses, locale } = this.props;

    const style = {
      container: {
        padding: '10px',
        backgroundColor: '#eeeeee',
        textAlign: 'center'
      }
    };

    if (courses.isFetching) {
      return (
        <div style={style.container}>
          {I18n.t('update.updating', { locale })}
        </div>
      );
    }

    if (updates.isFetching) {
      return (
        <div style={style.container}>
          {I18n.t('update.checking', { locale })}
        </div>
      );
    }

    if (updates.hasUpdates) {
      return (
        <div style={style.container}>
          <p>
            {I18n.t('update.download', {
              amount: Math.round(1000 * updates.size / 1024) / 1000,
              locale
            })}
          </p>
          <RaisedButton
            label={I18n.t('update.label', { locale })}
            primary={true}
            onClick={this.handleLoad}
          />
        </div>
      );
    }

    if (this.state.alreadyUpToDate) {
      return (
        <div style={style.container}>
          {I18n.t('update.upToDate', { locale })}
        </div>
      );
    }

    return null;
  }
}

function mapStateToProps({ updates, courses, settings: { locale } }) {
  return { updates, courses, locale };
}

export default connect(mapStateToProps)(Updates);
