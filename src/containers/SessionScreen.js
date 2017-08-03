import React, { Component } from "react";
import { Link } from "react-router-dom";
import { List, ListItem } from "material-ui/List";
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import { connect } from "react-redux";
import courseManager from "../services/CourseManager";
import FolderScreen from "./FolderScreen";

class SessionScreen extends Component {
  leftIcon(index) {
    return (
      <div className="session-index">{index}</div>
    )
  }

  render() {
    let { folder, course } = this.props;

    console.log('rendering SessionScreen');

    return (
      <div>
        <h1>Sessions</h1>
        <List>
          {folder !== undefined && folder.sessions.map((session, index) => {
            return (
              <Link key={session.uuid} to={`/courses/${course.uuid}/session/${session.uuid}`}>
                <ListItem
                  leftAvatar={this.leftIcon(index)}
                  key={session.uuid}
                  primaryText={session.title}
                  rightIcon={<Arrow/>}
                />
              </Link>
            )
          })}
        </List>
        <ul>
          <li><Link to="/">Home</Link></li>
        </ul>
      </div>
    );
  }
}

/**
 * @param {Object} props
 * @return {{folder: (Object|undefined)}}
 */
function mapStateToProps(state, props) {
  let course = courseManager.getCourse(props.match.params.courseId);
  let folderId = props.match.params.folderId || FolderScreen.DEFAULT_FOLDER;

  if (course === undefined) return {};

  let folder = courseManager.getFolderFromCourse(course, folderId);

  return { folder, course };
}

export default connect(mapStateToProps)(SessionScreen);
