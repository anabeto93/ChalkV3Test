import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Redirect } from 'react-router-dom';
import { COURSES, LOGIN } from '../config/routes';
import { LOGIN_STATE_LOGGED_IN } from '../store/defaultState';
import I18n from 'i18n-js';
import { Card, CardActions, CardHeader, CardText } from 'material-ui/Card';
import TextField from 'material-ui/TextField';
import RaisedButton from 'material-ui/RaisedButton';
import generateUrl from '../services/generateUrl';

export class HomeScreen extends Component {
  constructor(props) {
    super(props);
    this.state = {
      token: ''
    };
  }

  handleChange = e => {
    this.setState({
      token: e.target.value
    });
  };

  handleLogin = () => {
    const { history } = this.props;
    return history.push(
      generateUrl(LOGIN, {
        ':token': this.state.token
      })
    );
  };

  render() {
    if (this.props.loggedIn) {
      return <Redirect to={COURSES} />;
    }

    return (
      <div className="screen-centered">
        <Card>
          <CardHeader
            title={I18n.t('login.title')}
            subtitle={I18n.t('login.message')}
            actAsExpander={true}
            showExpandableButton={true}
            style={{ textAlign: 'left' }}
          />

          <CardText>
            <TextField
              fullWidth={true}
              hintText={I18n.t('login.tokenPlaceholder')}
              onChange={this.handleChange}
              value={this.state.token}
            />
          </CardText>

          <CardActions>
            <RaisedButton
              primary={true}
              label={I18n.t('login.title')}
              onClick={this.handleLogin}
              disabled={!(this.state.token.length === 6)}
            />
          </CardActions>

          <CardText expandable={true}>
            {I18n.t('login.hint')}
          </CardText>
        </Card>
      </div>
    );
  }
}

function mapStateToProps({ currentUser: { loginState } }) {
  return { loggedIn: loginState === LOGIN_STATE_LOGGED_IN };
}

export default connect(mapStateToProps)(HomeScreen);
