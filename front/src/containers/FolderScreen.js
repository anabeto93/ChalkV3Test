import I18n from 'i18n-js';
import { List, ListItem, ListItemText, Divider } from '@material-ui/core';
import Arrow from '@material-ui/icons/KeyboardArrowRight';
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
    const { course, folders, locale } = this.props;
    const totalFolders = Object.keys(folders).length;
    const firstFolder = folders[Object.keys(folders)[0]];

    return (
      <div>
        {totalFolders === 1 &&
          firstFolder.isDefault &&
          <Redirect
            to={generateUrl(SESSION_LIST_WITHOUT_FOLDER, {
              ':courseUuid': course.uuid
            })}
          />}

        {totalFolders === 0
          ? <p className="screen-centered alert">
              {I18n.t('course.noContentAvailable', { locale })}
            </p>
          : <List>
              {Object.keys(folders).map((key, index) => {
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
                    <ListItem button>
                      <ListItemText primary={folder.title} />
                      <Arrow />
                    </ListItem>

                    {index < totalFolders - 1 && <Divider />}
                  </Link>
                );
              })}
            </List>}
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

  return { course, folders, locale: state.settings.locale };
}

export default connect(mapStateToProps)(FolderScreen);
