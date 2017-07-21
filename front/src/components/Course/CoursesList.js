import _ from "lodash";
import {List, ListItem} from "material-ui/List";
import {Link} from "react-router-dom";
import React, {Component} from "react";

export class CoursesList extends Component {
  shouldComponentUpdate(nextProps) {
    if (!this.props.courses) {
      return true;
    }

    if (!_.isEqual(this.props.courses, nextProps.courses)) {
      return true;
    }

    return false;
  }

  render() {
    const {courses} = this.props;

    console.log('rendering CoursesList');

    return (
      <List>
        {undefined !== courses && courses.map(course => {
          return (
            <Link key={course.uuid} to={`/courses/${course.uuid}/folders/list`}>
              <ListItem
                primaryText={course.title}
                secondaryText={course.teacherName}
              />
            </Link>
          );
        })}
      </List>
    );
  }
}

export default CoursesList;
