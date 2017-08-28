import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Link } from 'react-router-dom';

import courseManager from '../services/CourseManager';
import FolderScreen from './FolderScreen';

class SessionScreen extends Component {
  leftIcon(index) {
    return (
      <div className="session-index">
        {index}
      </div>
    );
  }

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
                  to={`/courses/${course.uuid}/session/${session.uuid}`}
                >
                  <ListItem
                    leftAvatar={this.leftIcon(index)}
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

  const folderUuid =
    props.match.params.folderUuid || FolderScreen.DEFAULT_FOLDER;

  if (course === undefined) return {};

  const sessions = courseManager.getSessionsFromFolder(
    state.content.sessions,
    folderUuid
  );

  return { sessions, course };
}

export default connect(mapStateToProps)(SessionScreen);
