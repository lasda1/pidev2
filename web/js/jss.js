// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");
var btn2 = document.getElementById("myBtn2");

// Get the <span> element that closes the modal
var span = document.getElementById("out");
function modal1() {
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
}

// When the user clicks on the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

function modal2(i) {

    document.getElementById("update"+i).style.display = "block";
    window.onclick = function(event) {
        if(event.target == document.getElementById("update"+i)) {
            document.getElementById("update"+i).style.display = "none";
        }
    }
}
function modal3(i) {

    document.getElementById("afficher"+i).style.display = "block";
    window.onclick = function(event) {
        if(event.target == document.getElementById("afficher"+i)) {
            document.getElementById("afficher"+i).style.display = "none";
        }
    }
}
$(function(){
    $("#upload_link").on('click', function(e){
        e.preventDefault();
        $("#upload:hidden").trigger('click');
    });
});
function out(i) {
    document.getElementById("update"+i).style.display = "none";

}
function out2(i) {
    document.getElementById("afficher"+i).style.display = "none";

}
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}




// When the user clicks anywhere outside of the modal, close it


function test(i) {
    document.getElementById("test"+i).style.backgroundColor=" #e6e6e6";
    document.getElementById("name"+i).style.display="none";
document.getElementById("action"+i).style.display="block";

}
function test2(i) {
    document.getElementById("test"+i).style.backgroundColor=" #f2f2f2";
    document.getElementById("name"+i).style.display="block";
    document.getElementById("action"+i).style.display="none";

}
$("#recherche-content").hide();
function recherche() {



        var input=$("#docName").val();
        $.ajax({
            url:"{{ path('recherche_document') }}",
            type: "POST",
            data:{'docName':input},
            success : function (data){
                alert(data);

            }
        })


}