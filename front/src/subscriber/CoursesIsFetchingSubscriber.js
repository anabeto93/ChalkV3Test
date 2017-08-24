import { reinitUpdates } from '../actions/actionCreators';
import store from '../store/store';

let currentIsFetchingValue;

export default function CoursesIsFetchingSubscriber() {
  let previousValue = currentIsFetchingValue;
  const content = store.getState().content;
  currentIsFetchingValue = content.isFetching;

  if (previousValue && !currentIsFetchingValue && !content.isErrorFetching) {
    // if courses isFetching changed from true to false, reinit updates
    store.dispatch(reinitUpdates());
  }
}
