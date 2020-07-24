$(document).ready(function (e) {
	//$("#successo").hide();
    $('.form').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
            	//reload page to show result
                location.reload();              
            },
            error: function(data){
                alert("Errore");
            }
        });
     }));
     $('.eliminare').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                location.reload();
            },
            error: function(data){
                alert("Errore");
            }
        });
     }));
});
