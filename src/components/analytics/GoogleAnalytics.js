import { Component } from 'react';
import ReactGA from 'react-ga';
import { connect } from 'react-redux';
import getConfig from '../../config/index';

class GoogleAnalytics extends Component {
  constructor(props) {
    super(props);

    //Initialization
    ReactGA.initialize(getConfig().googleAnalytics, {
      gaOptions: {
        userId: props.token
      }
    });

    // Initial page load - only fired once
    this.sendPageChange(props.location.pathname, props.location.search);
  }

  componentWillReceiveProps(nextProps) {
    //When props change, check if the URL has changed too
    if (
      this.props.location.pathname !== nextProps.location.pathname ||
      this.props.location.search !== nextProps.location.search
    ) {
      this.sendPageChange(
        nextProps.location.pathname,
        nextProps.location.search
      );
    }
  }

  sendPageChange(pathname, search = '') {
    const page = pathname + search;
    ReactGA.set({ page });
    ReactGA.pageview(page);
  }

  render() {
    return null;
  }
}

function mapStateToProps(state) {
  return { token: state.currentUser.token };
}

export default connect(mapStateToProps)(GoogleAnalytics);
