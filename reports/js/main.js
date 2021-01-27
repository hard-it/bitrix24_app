$(document).ready(function () {
    $("#download-events").on('click', function(){
        $("#table-events").table2excel({
            filename: "events.xls"
        });
    });
    $("#download-services").on('click', function(){
        $("#table-services").table2excel({
            filename: "secrvices.xls"
        });
    });
    $('.nav-link').on('click', function(e){
        e.preventDefault();
        let id = $(this).attr('href');
        $('.tab-pane, .nav-link').removeClass('active');
        $(this).addClass('active');
        $(`${id}`).addClass('active');
    })
});