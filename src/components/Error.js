import RaisedButton from 'material-ui/RaisedButton';
import React, { Component } from 'react';

class Error extends Component {
  constructor(...args) {
    super(...args);
    this.state = {
      show: this.props.show
    };
  }

  toggleShow = () => {
    this.setState({ ...this.state, show: !this.state.show });
  };

  render() {
    const style = {
      container: {
        backgroundColor: '#eeeeee',
        padding: '5px',
        margin: '5px 0 5px 0',
        textAlign: 'center'
      }
    };

    return (
      <div style={style.container} className={!this.state.show ? 'hidden' : ''}>
        <p>
          {this.props.message}
        </p>
        <RaisedButton label="Dismiss" onClick={this.toggleShow} />
      </div>
    );
  }
}

Error.defaultProps = {
  show: false
};

export default Error;
