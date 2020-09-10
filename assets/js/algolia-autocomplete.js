$(document).ready(function () {
    $('.js-cpu-class').each(function() {
        let autocompleteUrl = $(this).data('autocomplete-url');
        $(this).autocomplete({hint: false}, [
            {
                source: function (query, cb) {
                    $.ajax({
                        url: autocompleteUrl+'?query='+query,
                        dataType: "json"
                    }).then(function (data) {
                        cb(data)
                    })
                },
                displayKey: 'Name',
                debounce: 200
            }
        ])
    });
});