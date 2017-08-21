import { RaisedButton } from 'material-ui';
import React from 'react';
import { withRouter } from 'react-router-dom';

const footer = props => {
  const { courseUuid, sessionUuid, history } = props;

  const handleNext = () => {
    history.push(`/courses/${courseUuid}/session/${sessionUuid}/send`);
  };

  return (
    <footer style={{ marginTop: '10px' }}>
      <RaisedButton
        primary={true}
        className="button-primary"
        onClick={handleNext}
      >
        Next
      </RaisedButton>
    </footer>
  );
};

export default withRouter(footer);
