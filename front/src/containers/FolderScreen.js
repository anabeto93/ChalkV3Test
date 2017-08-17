import { Link, Redirect } from 'react-router-dom';
import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import courseManager from '../services/CourseManager';

class FolderScreen extends Component {
  static DEFAULT_FOLDER = 'default';

  constructor(props) {
    super(props);

    if (props.course !== undefined) {
      document.title = props.course.title;
    }
  }

  render() {
    const { course } = this.props;

    return (
      <div>
        {course !== undefined &&
          course.folders.length > 0 &&
          course.folders[0].uuid === FolderScreen.DEFAULT_FOLDER &&
          <Redirect to={`/courses/${course.uuid}/sessions/list`} />}

        {course !== undefined && 0 === course.folders.length
          ? <p>No content available</p>
          : ''}

        <List>
          {course !== undefined &&
            course.folders.map(folder => {
              return (
                <Link
                  key={folder.uuid}
                  to={`/courses/${course.uuid}/folders/${folder.uuid}/sessions/list`}
                >
                  <ListItem primaryText={folder.title} rightIcon={<Arrow />} />
                </Link>
              );
            })}
        </List>
      </div>
    );
  }
}

function mapStateToProps(state, ownProps) {
  const course = courseManager.getCourse(
    state.courses.items,
    ownProps.match.params.courseUuid
  );

  return { course };
}

export default connect(mapStateToProps)(FolderScreen);
