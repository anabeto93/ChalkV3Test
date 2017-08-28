import spoolContentLoader from '../services/updates/spoolContentLoader';
import spoolFileLoader from '../services/updates/spoolFileLoader';
import store from '../store/store';
import { spoolTerminated } from '../actions/actionCreators';

let currentSpoolSessionFilesCount = 0;
let currentSpoolSessionTextCount = 0;

export default function CoursesSpoolSubscriber() {
  const previousSpoolSessionFilesCount = currentSpoolSessionFilesCount;
  const previousSpoolSessionTextCount = currentSpoolSessionTextCount;

  const spool = store.getState().content.spool;
  currentSpoolSessionTextCount = spool.sessionText.length;
  currentSpoolSessionFilesCount = spool.sessionFiles.length;

  if (
    previousSpoolSessionFilesCount + previousSpoolSessionTextCount > 0 &&
    currentSpoolSessionFilesCount + currentSpoolSessionTextCount === 0
  ) {
    store.dispatch(spoolTerminated());
    return;
  }

  if (
    currentSpoolSessionFilesCount !== previousSpoolSessionFilesCount &&
    currentSpoolSessionFilesCount > 0
  ) {
    spoolFileLoader.handle();
  }

  if (
    currentSpoolSessionTextCount !== previousSpoolSessionTextCount &&
    currentSpoolSessionTextCount > 0
  ) {
    spoolContentLoader.handle();
  }
}
