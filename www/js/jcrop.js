$(function(){

    $("#frm-coasterForm-form-imageFront").change(function(){
        readURL(this, "image-front-wrapper");
    });
    
    $("#frm-coasterForm-form-imageBack").change(function(){
        readURL(this, "image-back-wrapper");
    });
    
});

function readURL(input, wrapper) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
        	var id = $("." + wrapper).children('img').attr('id');
            $('.' + wrapper + ' .jcrop-holder').remove();
            $('#' + id).replaceWith('<img id="' + id + '" src="' + e.target.result + '">');
            $('#' + id).Jcrop({
                setSelect: [0,0,250,250],
                aspectRatio: 1 / 1,
                bgOpacity:   .4,
                onChange: function(coords){updateCoords(coords, id);},
                onSelect: function(coords){updateCoords(coords, id);}
            });
        }

        reader.readAsDataURL(input.files[0]);

    }

}

// JCROP
function updateCoords(coords, id) {
    $('#' + id + '-x1').val(coords.x);
	$('#' + id + '-y1').val(coords.y);
	$('#' + id + '-w').val(coords.w);
	$('#' + id + '-h').val(coords.h);
    $('#' + id + '-iw').val($("#" + id).width());
	$('#' + id + '-ih').val($("#" + id).height());
}

