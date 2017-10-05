import { List, ListItem } from 'material-ui/List';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Link, Redirect } from 'react-router-dom';

import courseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';
import { SESSION_LIST, SESSION_LIST_WITHOUT_FOLDER } from '../config/routes';

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
          firstFolder.isDefault &&
          <Redirect
            to={generateUrl(SESSION_LIST_WITHOUT_FOLDER, {
              ':courseUuid': course.uuid
            })}
          />}

        {totalFolders === 0 ? <p>No content available</p> : ''}

        <List>
          {Object.keys(folders).map(key => {
            let folder = folders[key];
            return (
              <Link
                className="link-primary"
                key={folder.uuid}
                to={generateUrl(SESSION_LIST, {
                  ':courseUuid': course.uuid,
                  ':folderUuid': folder.uuid
                })}
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
