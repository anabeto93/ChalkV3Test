import I18n from 'i18n-js';
import { Button } from '@material-ui/core';
import Arrow from '@material-ui/icons/KeyboardArrowRight';
import React from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import sessionNext from '../services/session/sessionNext';

const footer = props => {
  const { session, locale } = props;

  return (
    <footer className="next-session-footer background-grey">
      <Button
        variant="raised"
        color="primary"
        onClick={() => sessionNext(props)}
        fullWidth
      >
        {I18n.t(
          session.questions && !session.validated
            ? 'session.quizButton'
            : 'session.nextButton',
          { locale }
        )}
        <Arrow />
      </Button>
    </footer>
  );
};

function mapStateToProps(state) {
  return { sessions: state.content.sessions, locale: state.settings.locale };
}

export default withRouter(connect(mapStateToProps)(footer));
