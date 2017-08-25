import { RaisedButton } from 'material-ui';
import React from 'react';
import { withRouter } from 'react-router-dom';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';

const footer = props => {
  const { courseUuid, sessionUuid, history } = props;

  const handleNext = () => {
    history.push(`/courses/${courseUuid}/session/${sessionUuid}/send`);
  };

  return (
    <footer style={{ marginTop: '10px' }}>
      <RaisedButton
        label="Next"
        labelPosition="before"
        primary={true}
        className="button-primary"
        onClick={handleNext}
        icon={<Arrow/>}
      />
    </footer>
  );
};

export default withRouter(footer);
