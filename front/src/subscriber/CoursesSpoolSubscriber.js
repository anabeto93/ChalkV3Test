import spoolFileLoader from '../services/updates/spoolFileLoader';
import store from '../store/store';
import { spoolTerminated } from '../actions/actionCreators';

let totalCurrentValue;

export default function CoursesSpoolSubscriber() {
  let totalPreviousValue = totalCurrentValue;
  const spool = store.getState().courses.spool;
  totalCurrentValue = spool.total;

  //const currentSpool = spool.sessionText + spool.sessionFiles;
  const currentSpool = spool.sessionFiles;

  if (0 === currentSpool.length && totalCurrentValue > 0) {
    store.dispatch(spoolTerminated());
    return;
  }

  if (totalCurrentValue !== totalPreviousValue && totalCurrentValue > 0) {
    spoolFileLoader.handle();
  }
}
