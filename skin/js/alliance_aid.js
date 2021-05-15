
// Old Export Options
var oldExportAction = function(self, e, dt, button, config) {
    if (button[0].className.indexOf('buttons-excel') >= 0) {
        if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
        } else {
            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
        }
    } else if (button[0].className.indexOf('buttons-print') >= 0) {
        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
    }
};

// New Export Options
var newExportAction = function(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;

    dt.one('preXhr', function(e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;

        dt.one('preDraw', function(e, settings) {
            // Call the original action function 
            oldExportAction(self, e, dt, button, config);

            dt.one('preXhr', function(e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });

            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);

            // Prevent rendering of the full data to the DOM
            return false;
        });
    });

    // Requery the server with the new one-time export settings
    dt.ajax.reload();
};


function _parse(obj) {
    parsed = [];
    for (index = 0; index < obj.length; index++) {
        parsed.push({
            "value": obj[index]['name'],
            "display": obj[index]['name']
        })

    }
    return parsed;
}

function _dic(obj) {
    parsed = {};
    for (index = 0; index < obj.length; index++) {
        parsed[obj[index]['name']] = obj[index]['id'];
    }
    return parsed;
}

function parse_multi_select(object) {
    parsed = [];
    parsed.push({
        value: '^$',
        label: 'Empty'
    });
    parsed.push({
        value: '(.)+',
        label: 'Not Empty'
    });
    for (index = 0; index < object.length; index++) {
        parsed.push({
            value: object[index],
            label: object[index]
        });
    }
    return parsed;
}


function get_option(object) {
    parsed = [];
    parsed.push({
        value: '^$',
        label: 'Empty'
    });
    parsed.push({
        value: '(.)+',
        label: 'Not Empty'
    });
    for (index = 0; index < object.length; index++) {
        parsed.push({
            value: object[index],
            label: object[index]
        });
    }
    return parsed;
}