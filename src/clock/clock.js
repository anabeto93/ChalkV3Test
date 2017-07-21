import store from '../store/store';
import { getUpdates } from '../actions/actionCreators';
import getConfig from '../config';

const intervalInSecondsUpdates = getConfig().updates.intervalInSeconds;

const clock = setInterval(checkUpdates, intervalInSecondsUpdates * 1000);

// check updates when starting
checkUpdates();

function checkUpdates() {
  console.log('checkUpdates');
  const { isFetching } = store.getState().updates;

  if (!isFetching) {
    console.log('getUpdates');
    store.dispatch(getUpdates());
  }
}

if (module.hot) {
  module.hot.accept();
  module.hot.dispose(() => {
    clearInterval(clock);
  });
}
