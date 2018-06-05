import React from 'react';
import { Typography, LinearProgress } from '@material-ui/core';

const Loader = () =>
  <div className="screen-centered">
    <Typography variant="display2">Loading...</Typography>
    <LinearProgress color="secondary" />
  </div>;

export default Loader;
