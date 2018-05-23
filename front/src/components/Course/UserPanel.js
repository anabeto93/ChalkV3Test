import React from 'react';
import { Card, CardHeader } from '@material-ui/core';
import { connect } from 'react-redux';

class UserPanel extends React.Component {
  subtitle() {
    return (
      <div>
        <div>
          {this.props.user.country}
        </div>
        <div>
          {this.props.user.phoneNumber}
        </div>
      </div>
    );
  }

  render() {
    if (this.props.user.uuid !== undefined) {
      return (
        <Card>
          <CardHeader
            title={`${this.props.user.firstName} ${this.props.user.lastName}`}
            subheader={this.subtitle()}
          />
        </Card>
      );
    }

    return <div />;
  }
}

function mapStateToProps({ currentUser }) {
  return { user: currentUser };
}

export default connect(mapStateToProps)(UserPanel);
