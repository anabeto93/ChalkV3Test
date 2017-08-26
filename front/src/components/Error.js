import React, { Component } from 'react';
import RaisedButton from 'material-ui/RaisedButton';

class Error extends Component {
  constructor(...args) {
    super(...args);
    this.state = {
      show: this.props.show
    }
  }

  toggleShow = () => {
    this.setState({ ...this.state, show: !this.state.show });
  };

  render() {
    const style = {
      container: {}
    };

    return (
      <div style={style.container} className={!this.state.show ? 'hidden' : ''}>
        <p>{this.props.message}</p>
        <RaisedButton label="Dismiss" onClick={this.toggleShow}/>
      </div>
    )
  }
}

Error.defaultProps = {
  show: false
};

export default Error;
