import { Link } from "react-router-dom";
import { List, ListItem } from "material-ui/List";
import React, { Component } from "react";
import { connect } from 'react-redux';
import courseManager from "../services/CourseManager";

export class FolderScreen extends Component {
  render() {
    const course = this.props.course;

    return (
      <div>
        <h1>Folders</h1>
        <List>
          { course !== undefined && course.folders.map((folder) => {
            return (
              <ListItem key={folder.uuid} primaryText={folder.title}/>
            )
          }) }
        </List>
        <Link to="/">Home</Link>
      </div>
    );
  }
}

function mapStateToProps({}, ownProps) {
  let course = courseManager.getCourse(ownProps.match.params.courseId);

  return { course };
}

export default connect(mapStateToProps)(FolderScreen);
