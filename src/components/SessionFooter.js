import { RaisedButton } from 'material-ui';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import CourseManager from '../services/CourseManager';

const footer = props => {
  const { courseUuid, session, sessions, history } = props;

  const handleNext = () => {
    if (session.needValidation) {
      return history.push(
        `/courses/${courseUuid}/session/${session.uuid}/send`
      );
    }

    const nextSession = CourseManager.getNextSession(sessions, session);

    if (nextSession !== null) {
      return history.push(
        `/courses/${nextSession.courseUuid}/session/${nextSession.uuid}`
      );
    }

    return history.push(
      `/courses/${session.courseUuid}/folders/${session.folderUuid}/sessions/list`
    );
  };

  return (
    <footer style={{ marginTop: '10px' }}>
      <RaisedButton
        label="Next"
        labelPosition="before"
        primary={true}
        className="button-primary"
        onClick={handleNext}
        icon={<Arrow />}
      />
    </footer>
  );
};

function mapStateToProps(state) {
  return { sessions: state.content.sessions };
}

export default connect(mapStateToProps)(withRouter(footer));
