import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Redirect } from 'react-router-dom';
import { COURSES, LOGIN } from '../config/routes';
import { LOGIN_STATE_LOGGED_IN } from '../store/defaultState';
import I18n from 'i18n-js';
import {
  Card,
  CardActions,
  CardHeader,
  CardContent,
  TextField,
  Button,
  IconButton,
  Collapse,
  Typography
} from '@material-ui/core';
import HelpIcon from '@material-ui/icons/Help';
import generateUrl from '../services/generateUrl';

export class HomeScreen extends Component {
  constructor(props) {
    super(props);
    this.state = {
      token: '',
      expanded: false
    };
  }

  handleChange = e => {
    this.setState({
      token: e.target.value
    });
  };

  handleKeyPress = e => {
    if (this.state.token.length === 6 && e.key === 'Enter') {
      this.handleLogin();
    }
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
            subheader={I18n.t('login.message')}
            action={
              <IconButton
                onClick={() =>
                  this.setState({ expanded: !this.state.expanded })}
              >
                <HelpIcon />
              </IconButton>
            }
          />

          <CardContent>
            <TextField
              fullWidth={true}
              placeholder={I18n.t('login.tokenPlaceholder')}
              onChange={this.handleChange}
              onKeyPress={this.handleKeyPress}
              value={this.state.token}
            />
          </CardContent>

          <CardActions>
            <Button
              variant="raised"
              color="primary"
              onClick={this.handleLogin}
              disabled={!(this.state.token.length === 6)}
              fullWidth
            >
              {I18n.t('login.title')}
            </Button>
          </CardActions>

          <Collapse in={this.state.expanded} timeout="auto" unmountOnExit>
            <CardContent>
              <Typography
                dangerouslySetInnerHTML={{ __html: I18n.t('login.hint') }}
              />
            </CardContent>
          </Collapse>
        </Card>
      </div>
    );
  }
}

function mapStateToProps({ currentUser: { loginState } }) {
  return { loggedIn: loginState === LOGIN_STATE_LOGGED_IN };
}

export default connect(mapStateToProps)(HomeScreen);
