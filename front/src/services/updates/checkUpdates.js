import moment from 'moment';

import getConfig from '../../config/index';
import { getUpdates } from '../../actions/actionCreators';
import store from '../../store/store';

export default function checkUpdates() {
  const { isFetching, dateLastCheck } = store.getState().updates;

  let isOutOfDate;

  if (null === dateLastCheck) {
    isOutOfDate = true;
  } else {
    const intervalInSecondsUpdates = getConfig().updates.intervalInSeconds;
    const dateNextCheck = moment(dateLastCheck).add(
      intervalInSecondsUpdates,
      'seconds'
    );
    const now = moment();
    isOutOfDate = dateNextCheck.isBefore(now);
  }

  if (!isFetching && isOutOfDate) {
    store.dispatch(getUpdates());
  }
}
