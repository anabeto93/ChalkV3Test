import { setNetworkStatus } from '../../actions/actionCreators';
import store from '../../store/store';

// init
if ('onLine' in window.navigator) {
  store.dispatch(setNetworkStatus(window.navigator.onLine));

  window.addEventListener(
    'offline',
    () => store.dispatch(setNetworkStatus(false)),
    false
  );
  window.addEventListener(
    'online',
    () => store.dispatch(setNetworkStatus(true)),
    false
  );
}
