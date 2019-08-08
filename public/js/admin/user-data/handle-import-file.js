function importFile(id, isSave) {
    if(!id){
        return false;
    }

    $("#preloader-posts-" + id).show();

    $.ajax({
        url: "/admin-import-file-specific-advert/" + id,
        data: {
            isSave : isSave
        },
        method: "POST",
    }).done(function(response) {
        if(isSave) {
            $("#import-count-success-" + id).text(response["countImported"]);
            $("#import-count-failed-" + id).text(response["countLines"] - response["countImported"]);
        }
        else{
            $("#check-count-success-" + id).text(' (' + response["countImported"] + ')');
            $("#check-count-failed-" + id).text(' (' + (response["countLines"] - response["countImported"]) + ')');
        }

        $("#preloader-posts-" + id).hide();
    });
}

function deleteFile(id) {
    console.log(id);

    let confirmRemove = confirm("Вы действительно хотите удалить этот файл?");

    if(!confirmRemove){
        return false;
    }

    $.ajax({
        url: "/admin-remove-file-specific-advert/" + id,
        method: "POST",
    }).done(function(response) {
        if(response.status){
            $("td[objectid=" + id + "]").parent().remove();
        }
    });
}