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

    console.log('rendering FolderScreen');

    return (
      <div>
        {course !== undefined &&
          course.folders.length > 0 &&
          course.folders[0].uuid === FolderScreen.DEFAULT_FOLDER &&
          <Redirect to={`/courses/${course.uuid}/sessions/list`} />}

        <List>
          {course !== undefined &&
            course.folders.map(folder => {
              return (
                <Link
                  className="link-primary"
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
  let course = courseManager.getCourse(ownProps.match.params.courseId);

  return { course };
}

export default connect(mapStateToProps)(FolderScreen);
