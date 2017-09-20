import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Link } from 'react-router-dom';

import courseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';
import { SESSION_DETAIL } from '../config/routes';
import getConfig from '../config/index';

class SessionScreen extends Component {
  leftIcon = (index, validated = false) => {
    let iconClass = 'session-index';

    if (validated) {
      iconClass += ' session-index-validated';
    }

    return (
      <div className={iconClass}>
        {index}
      </div>
    );
  };

  render() {
    const { sessions, course } = this.props;
    const totalSession = Object.keys(sessions).length;

    return (
      <div>
        {totalSession === 0 ? <p>No content available</p> : ''}

        <List>
          {sessions !== undefined &&
            Object.keys(sessions).map((key, index) => {
              let session = sessions[key];
              return (
                <Link
                  key={session.uuid}
                  to={generateUrl(SESSION_DETAIL, {
                    ':courseUuid': course.uuid,
                    ':sessionUuid': session.uuid
                  })}
                >
                  <ListItem
                    leftAvatar={this.leftIcon(index + 1, session.validated)}
                    key={session.uuid}
                    primaryText={session.title}
                    rightIcon={<Arrow />}
                  />
                </Link>
              );
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

  return { sessions, course };
}

export default connect(mapStateToProps)(SessionScreen);
