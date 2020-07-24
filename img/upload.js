$(document).ready(function (e) {
     $('.eliminare').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        alert("ciao");
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
