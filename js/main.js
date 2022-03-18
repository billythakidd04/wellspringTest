<!-- include attach submit handler form -->
$(document).ready(function () {
    $.get('/checkHistory', function (previousData) {
        if (previousData !== "") {
            $("#main").append(genTableData(previousData));
        }
    });

    $("#clear").click(function (event) {
        $.post('/clear');
        $("table").remove();
    });

    $("#file").submit(function (event) {
        event.preventDefault();
        // we only ever accept one file
        const file = $("#csvInput").prop('files')[0];
        if (file === undefined) {
            alert('no file added');
            return false;
        }
        console.log(file.type);
        // only accept csv (allowing for other Excel files as that's what the csv was turned into when I opened it
        if (!file.type.match('application/vnd.ms-excel') && !file.type.match('text/csv')) {
            alert('Invalid file type please reload page and use csv');
        }

        // add the file to send off to the api
        const fd = new FormData();
        fd.append('files[]', file, file.name);
        // send the post call with the file and prevent jQuery from abusing it
        $.ajax({
            url: '/read',
            type: 'post',
            data: fd,
            processData: false,
            contentType: false,
            success: function (data) {
                $("#main").append(genTableData(data))
            }
        });
        return false;
    });
});

function sortJson(unsorted) {
    const data = JSON.parse(unsorted);
    return data.sort(function (a, b) {
        let x = a['RUN_NUMBER'];
        let y = b['RUN_NUMBER'];
        console.log((x < y));
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}

function genTableData(data) {
    const sortedData = sortJson(data);
    // build table based on json data
    let table = "<table id='trainTable'>";
    let tblBody = "";
    // foreach object add to table
    let header = false;
    let headerRow = '';
    $.each(sortedData, function () {
        let row = "";
        // add each value as field
        $.each(this, function (key, val) {
            //if we havent set the header yet
            if (header === false) {
                //build the header
                headerRow += "<th>" + key + "</th>";
            }
            // build the row
            row += "<td>" + val + "</td>";
        });
        // set the header if not done yet
        if (header === false) {
            table += headerRow;
            // mark header complete
            header = true;
        }
        // add the resulting row to the body
        tblBody += "<tr>" + row + "</tr>";
    });
    // replace empty body with completed result
    table += tblBody + "</table>";
    // replace main block with result from api
    return table;
}