function importFile(id, isSave) {
    if(!id){
        return false;
    }

    $("#preloader-posts-" + id).show();
    $("#action-import-result-" + id).hide();

    $.ajax({
        url: "/admin-import-file-specific-advert/" + id,
        data: {
            isSave : isSave
        },
        method: "POST",
    }).done(function(response) {
        $("#import-count-success-" + id).text(response["countImported"]);
        $("#import-count-failed-" + id).text(response["countLines"] - response["countImported"]);
        $("#action-import-result-" + id).show();

        $("#preloader-posts-" + id).hide();
    });
}