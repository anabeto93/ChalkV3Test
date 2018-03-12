import $ from 'jquery';
import 'bootstrap/dist/js/bootstrap';
import 'elao-form.js';

import Confirm from './components/Confirm';

function init(target) {
    $('[data-collection]', target).collection()
        .on('collection:added', function (event, item) { init(item.element.get(0)); })
        .on('collection:deleted', function (event, item) {
            // Refresh shared choices collection in sub collections
            $('[data-shared-choices-collection]').each(function (key, element) {
                const o = $(element).data('shared-choices-collection-object');
                if (o !== undefined) {
                    o.refresh();
                }
            });
        });

    [].forEach.call(target.querySelectorAll('[data-confirm]'), function (element) { new Confirm(element); });

    $('[data-toggle="popover"]').popover();

    $(document).ready(function() {
        $("#select_all").change(function () {
            $(":checkbox").prop('checked', $(this).prop("checked"));
        });

        $(':checkbox').change(function () {
            if (false === $(this).prop("checked")) {
                $("#select_all").prop('checked', false);
            }

            if ($(':checkbox:checked').length === $(':checkbox').length-1) {
                $("#select_all").prop('checked', true);
            }
        });
    });
}

init(document);
