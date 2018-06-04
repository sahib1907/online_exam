$('.delete').on('click', function (e) {
    e.preventDefault();

    var link = $(this).attr('href');

    var r = confirm('Are you sure to delete?');

    if (r) {
        location.href = link;
    }
});

$('.deleteCategory').on('click', function (e) {
    e.preventDefault();

    var link = $(this).attr('href');

    var r = confirm('When you delete this category, its subcategories and products will also be deleted. Do you still want to delete it?');

    if (r) {
        location.href = link;
    }
});

$('.deleteRows').on('click', function () {

    var module = $(this).attr('module');
    var allid = '';

    $('.rowDelete:checked').each(function () {
        var row_id = $(this).attr('row_id');
        allid += row_id + ",";
    });

    //ajax
    $.post("ajax/deletemany.php", {allid: allid, module: module}, function (data) {
        alert(data);
        location.reload();
    });
});

$('.logicalDeleteRows').on('click', function () {

    var module = $(this).attr('module');
    var type = $(this).attr('delete_type');
    var allid = '';

    $('.rowDelete:checked').each(function () {
        var row_id = $(this).attr('row_id');
        allid += row_id + ",";
    });

    //ajax
    $.post("ajax/logicaldeletemany.php", {allid: allid, module: module, type: type}, function (data) {
        alert(data);
        location.reload();
    });
});

$('.deleteimage').on('click', function () {

    var table = $(this).attr('table');
    var id = $(this).attr('unit_id');
    var image = $(this).attr('image');

    //ajax
    $.post("ajax/deleteimage.php", {table: table, id: id, image: image}, function (data) {
        alert(data);
        location.reload();
    });
    return false;
});

function tableSearch() {
    // Declare variables
    var input, filter, table, tr, td, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1];
        if (td) {
            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

function validateForm() {
    var pass = document.getElementById('password').value.length;
    var user = document.getElementById('username').value.length;

    if (user < 4 || user > 50) {
        alert("The username must be in the range of 4-50 symbols!");
        return false;
    }
    if (pass < 8) {
        alert("Password must be at least 8 symbols!");
        return false;
    }
}

function changeValue() {
    var fennid = $('#selectFenn').val();
    var module = $('#selectFenn').attr('module');

    $.post("ajax/fennselect.php", {fennid: fennid, module:module}, function (elem) {
        location.href=elem;
    });
}

//get groups
$("#uni").change(function(){
    var uni = $(this).val();

    //ajax
    $.post("ajax/getgroups.php", {uni: uni}, function (data) {
        $("#qrup").html(data);
        //alert(data);
        return false;
    });
});