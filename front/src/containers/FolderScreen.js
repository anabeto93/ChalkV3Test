import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Link, Redirect } from 'react-router-dom';
import getConfig from '../config/index';

import courseManager from '../services/CourseManager';

class FolderScreen extends Component {
  constructor(props) {
    super(props);

    if (props.course !== undefined) {
      document.title = props.course.title;
    }
  }

  render() {
    const { course, folders } = this.props;
    const totalFolders = Object.keys(folders).length;
    const firstFolder = folders[Object.keys(folders)[0]];

    return (
      <div>
        {totalFolders > 0 &&
          firstFolder.uuid === getConfig().defaultFolder &&
          <Redirect to={`/courses/${course.uuid}/sessions/list`} />}

        {totalFolders === 0 ? <p>No content available</p> : ''}

        <List>
          {Object.keys(folders).map(key => {
            let folder = folders[key];
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

function mapStateToProps(state, props) {
  const course = courseManager.getCourse(
    state.content.courses,
    props.match.params.courseUuid
  );

  const folders = courseManager.getFoldersFromCourse(
    state.content.folders,
    props.match.params.courseUuid
  );

  return { course, folders };
}

export default connect(mapStateToProps)(FolderScreen);
