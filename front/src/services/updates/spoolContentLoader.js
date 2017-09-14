import { getSessionContent } from '../../actions/actionCreators';
import store from '../../store/store';

export class SpoolContentLoader {
  handle() {
    const sessionUuidToLoad = store.getState().content.spool.sessionText[0];

    if (undefined === sessionUuidToLoad) {
      return;
    }

    store.dispatch(getSessionContent(sessionUuidToLoad));
  }
}

export default new SpoolContentLoader();
