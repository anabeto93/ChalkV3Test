import { Link } from "react-router-dom";
import { List, ListItem } from "material-ui/List";
import React, { Component } from "react";
import { connect } from "react-redux";
import courseManager from "../services/CourseManager";

export class FolderScreen extends Component {
  constructor(props) {
    super(props);

    if (props.course !== undefined) {
      document.title = props.course.title;
    }
  }

  render() {
    const course = this.props.course;

    console.log('rendering FolderScreen');

    return (
      <div>
        <h1>Folders</h1>
        <List>
          { course !== undefined && course.folders.map((folder) => {
            return (
              <Link key={folder.uuid} to={`/courses/${course.uuid}/folders/${folder.uuid}/sessions/list`}>
                <ListItem primaryText={folder.title}/>
              </Link>
            )
          }) }
        </List>
        <ul>
          <li><Link to="/">Home</Link></li>
          <li><Link to="/courses">Back</Link></li>
        </ul>
      </div>
    );
  }
}

function mapStateToProps({}, ownProps) {
  let course = courseManager.getCourse(ownProps.match.params.courseId);

  return { course };
}

export default connect(mapStateToProps)(FolderScreen);
