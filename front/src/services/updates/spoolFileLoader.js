import { fileLoaded } from '../../actions/actionCreators';
import store from '../../store/store';

export class SpoolFileLoader {
  constructor() {
    this.isLoading = false;
    this.increment = 0;
  }

  handle() {
    this.increment++;

    if (this.isLoading) {
      return;
    }

    const fileToLoad = store.getState().courses.spool.sessionFiles[0];

    if (undefined === fileToLoad) {
      return;
    }

    this.isLoading = true;

    fetch(fileToLoad)
      .then(() => {
        this.isLoading = false;
        store.dispatch(fileLoaded(fileToLoad));
        this.handle();
      })
      .catch(error => {
        this.isLoading = false;
        alert(
          `There has been a problem when loading: ${fileToLoad}; Error: ${error.message}`
        );
      });
  }
}

export default new SpoolFileLoader();
