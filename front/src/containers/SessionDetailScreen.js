import React, { Component } from 'react';
import { connect } from 'react-redux';
import SessionFooter from '../components/SessionFooter';
import CourseManager from '../services/CourseManager';
import JsxParser from 'react-jsx-parser';
import * as material from '@material-ui/core';
import * as icons from '@material-ui/icons';
import SelfTestQuestion from '../components/SelfTestQuiz/SelfTestQuestion';

const jsxComponents = Object.assign({}, material, icons, {
  SelfTestQuestion: SelfTestQuestion
});

class SessionDetailScreen extends Component {
  renderContent() {
    return { __html: this.props.session.content };
  }

  renderer() {
    const content = this.props.session.content;
    const jsxRegEx = /(?:<([A-Z][a-z]+)>(?:.|\n)*?<\/\1>|<[A-Z](?:[^>])+\/>)/g;
    const isJSX = jsxRegEx.test(content);

    if (isJSX) {
      return <JsxParser jsx={content} components={jsxComponents} />;
    }

    return (
      <div
        className="session-content"
        dangerouslySetInnerHTML={this.renderContent()}
      />
    );
  }

  render() {
    const { session, courseUuid } = this.props;

    if (session !== undefined) {
      return (
        <div>
          <div className="content">
            <h1>
              {session.title}
            </h1>

            <div className="session-content">
              {this.renderer()}
            </div>
          </div>

          <SessionFooter courseUuid={courseUuid} session={session} />
        </div>
      );
    }

    return <div />;
  }
}

function mapStateToProps(state, props) {
  const session = CourseManager.getSession(
    state.content.sessions,
    props.match.params.sessionUuid
  );

  return {
    session,
    courseUuid: props.match.params.courseUuid
  };
}

export default connect(mapStateToProps)(SessionDetailScreen);
