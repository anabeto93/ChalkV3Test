import { connect } from 'react-redux';
import RaisedButton from 'material-ui/RaisedButton';
import React, { Component } from 'react';

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
    const { updates, courses } = this.props;

    const style = {
      container: {
        padding: '10px',
        backgroundColor: '#eeeeee',
        textAlign: 'center'
      }
    };

    if (courses.isFetching) {
      return <div style={style.container}>Updating app...</div>;
    }

    if (updates.isFetching) {
      return <div style={style.container}>Checking updates...</div>;
    }

    if (updates.hasUpdates) {
      return (
        <div style={style.container}>
          <p>
            Your app must be updated ({Math.round(1000 * updates.size / 1024) /
              1000}{' '}
            kb to download)
          </p>
          <RaisedButton
            label="Update"
            primary={true}
            onClick={this.handleLoad}
          />
        </div>
      );
    }

    if (this.state.alreadyUpToDate) {
      return <div style={style.container}>Your app is already up to date!</div>;
    }

    return null;
  }
}

function mapStateToProps({ updates, courses }) {
  return { updates, courses };
}

export default connect(mapStateToProps)(Updates);
