import React from 'react';
import { Card, CardHeader } from 'material-ui/Card';
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
            subtitle={this.subtitle()}
            style={{ textAlign: 'left' }}
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
