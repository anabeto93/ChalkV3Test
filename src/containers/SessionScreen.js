import I18n from 'i18n-js';
import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Link } from 'react-router-dom';
import getConfig from '../config/index';
import { SESSION_DETAIL } from '../config/routes';

import courseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';

class SessionScreen extends Component {
  leftIcon = (index, validated = false, lastToRead = false) => {
    let iconClass = 'session-index';

    if (lastToRead) {
      iconClass += ' session-index-last-to-read';
    } else if (validated) {
      iconClass += ' session-index-validated';
    }

    return (
      <div className={iconClass}>
        {index}
      </div>
    );
  };

  render() {
    const { sessions, course, locale } = this.props;
    const totalSession = Object.keys(sessions).length;
    const sessionsArray = Object.keys(sessions);

    const lastToReadSessionKey = sessionsArray.find(key => {
      const session = sessions[key];
      return session.needValidation && !session.validated;
    });

    return (
      <div>
        {totalSession === 0
          ? <p>
              {I18n.t('course.noContentAvailable', { locale })}
            </p>
          : ''}

        <List>
          {sessions !== undefined &&
            sessionsArray.map((key, index) => {
              const session = sessions[key];
              const isLastToRead = lastToReadSessionKey === key;
              const enabledSession =
                session.validated || !session.needValidation || isLastToRead;

              if (enabledSession) {
                return (
                  <Link
                    key={session.uuid}
                    to={generateUrl(SESSION_DETAIL, {
                      ':courseUuid': course.uuid,
                      ':sessionUuid': session.uuid
                    })}
                    style={{ textDecoration: 'none' }}
                  >
                    <ListItem
                      leftAvatar={this.leftIcon(index + 1, true, isLastToRead)}
                      key={session.uuid}
                      primaryText={session.title}
                      rightIcon={<Arrow />}
                      style={isLastToRead ? { fontWeight: 'bold' } : {}}
                    />
                  </Link>
                );
              } else {
                return (
                  <ListItem
                    leftAvatar={this.leftIcon(index + 1, false, false)}
                    key={session.uuid}
                    primaryText={session.title}
                    disabled={true}
                    style={{
                      backgroundColor: '#ddd',
                      borderTop: 'solid 1px #ccc'
                    }}
                  />
                );
              }
            })}
        </List>
      </div>
    );
  }
}

/**
 * @param {Object} state
 * @param {Object} props
 * @return {{folder: (Object|undefined)}}
 */
function mapStateToProps(state, props) {
  const course = courseManager.getCourse(
    state.content.courses,
    props.match.params.courseUuid
  );

  let folderUuid = props.match.params.folderUuid;

  if (course === undefined) return {};

  if (undefined === folderUuid) {
    const defaultFolder = getConfig().defaultFolder;
    folderUuid = `${course.uuid}_${defaultFolder}`;
  }

  const sessions = courseManager.getSessionsFromFolder(
    state.content.sessions,
    folderUuid
  );

  const { settings: { locale } } = state;

  return { sessions, course, locale };
}

export default connect(mapStateToProps)(SessionScreen);
