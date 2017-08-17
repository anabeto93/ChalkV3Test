import { fileLoaded } from '../../actions/actionCreators';
import store from '../../store/store';

let isLoading = false;

export default function spoolFileLoader() {
  const fileToLoad = store.getState().courses.spool.sessionFiles.shift();

  if (undefined === fileToLoad || isLoading) {
    return;
  }

  isLoading = true;

  fetch(fileToLoad)
    .then(() => {
      isLoading = false;
      store.dispatch(fileLoaded(fileToLoad));
    })
    .catch(error => {
      isLoading = false;
      alert(
        `There has been a problem when loading: ${fileToLoad}; Error: ${error.message}`
      );
    });
}
