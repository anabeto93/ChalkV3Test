import { reinitUpdates } from '../actions/actionCreators';
import store from '../store/store';

let currentIsFetchingValue;

export default function CoursesIsFetchingSubscriber() {
  let previousValue = currentIsFetchingValue;
  const courses = store.getState().courses;
  currentIsFetchingValue = courses.isFetching;

  if (previousValue && !currentIsFetchingValue && !courses.isErrorFetching) {
    // if courses isFetching changed from true to false, reinit updates
    store.dispatch(reinitUpdates());
  }
}
