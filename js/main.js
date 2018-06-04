$('.delete').on('click', function (e) {
    e.preventDefault();

    var link = $(this).attr('href');

    var r = confirm('Are you sure to delete?');

    if (r) {
        location.href = link;
    }
});

count =  document.getElementById("hidden").value;

$('.addAnswer').on('click', function (e) {
    e.preventDefault();

    count++;

    $( ".addAns" ).append( '<label style="color: #ec1d38;"><strong>FƏNN ' + count + '</strong></label><div id="fenn' + count + '"><div class="form-group"> <label>Kredit sayı:</label> <input type="number" class="form-control" name="credit[]" required> </div> <div class="form-group"> <label>Bal:</label> <input type="number" class="form-control" name="point[]" required> </div> <hr> </div>' );
});

function changeValue() {
    var fennid = $('#selectFenn').val();

    if (fennid != 0) {
        $.post("ajax/getinterval.php", {fennid: fennid}, function (elem) {
            $('#interval').html(elem);
            return false;
        });
    }
    else {
        $.post("ajax/getinterval.php", {fennid: fennid}, function (elem) {
            $('#interval').html('<option value="f">Aralıq seçin</option>');
            return false;
        });
    }

}

//get groups
$("#uni").change(function(){
    var uni = $(this).val();

    console.log('ada');
    //ajax
    $.post("ajax/getgroups.php", {uni: uni}, function (data) {
        $("#qrup").html(data);
        //alert(data);
        return false;
    });
});