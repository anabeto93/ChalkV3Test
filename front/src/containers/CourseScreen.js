import I18n from 'i18n-js';
import React, { Component } from 'react';
import { connect } from 'react-redux';

import CoursesList from '../components/Course/CoursesList';
import UserPanel from '../components/Course/UserPanel';
import Error from '../components/Error';
import { doneValidateSession } from '../actions/actionCreators';

export class CourseScreen extends Component {
  render() {
    const { courses, isSessionFailValidating, locale } = this.props;

    return (
      <div>
        <CoursesList courses={courses} />
        {isSessionFailValidating &&
          <Error
            show={true}
            message={I18n.t('send.sms.validation.fail', { locale })}
            dispatchOnDismiss={doneValidateSession}
          />}
      </div>
    );
  }
}

function mapStateToProps({
  content: { courses, isSessionFailValidating },
  settings: { locale }
}) {
  return { courses, isSessionFailValidating, locale };
}

export default connect(mapStateToProps)(CourseScreen);
