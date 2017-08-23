import 'jquery';
import 'bootstrap/dist/js/bootstrap';

import Confirm from './components/Confirm';

function init(target) {
    [].forEach.call(target.querySelectorAll('[data-confirm]'), function (element) { new Confirm(element); });
}

init(document);
