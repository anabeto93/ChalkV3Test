import React, { Component } from 'react';
import { Link } from 'react-router-dom';
import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import { connect } from 'react-redux';
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
    let { folder, course } = this.props;

    return (
      <div>
        <List>
          {folder !== undefined &&
            folder.sessions.map((session, index) => {
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
 * @param {Object} props
 * @return {{folder: (Object|undefined)}}
 */
function mapStateToProps(state, props) {
  const course = courseManager.getCourse(
    state.courses.items,
    props.match.params.courseUuid
  );
  const folderUuid =
    props.match.params.folderUuid || FolderScreen.DEFAULT_FOLDER;

  if (course === undefined) return {};

  const folder = courseManager.getFolderFromCourse(course, folderUuid);

  return { folder, course };
}

export default connect(mapStateToProps)(SessionScreen);
