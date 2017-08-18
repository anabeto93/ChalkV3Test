import spoolFileLoader from '../services/updates/spoolFileLoader';
import store from '../store/store';

let currentValue;

export default function CoursesSpoolSubscriber() {
  let previousValue = currentValue;
  currentValue = store.getState().courses.spool.total;

  if (currentValue !== previousValue && currentValue > 0) {
    spoolFileLoader.handle();
  }
}
