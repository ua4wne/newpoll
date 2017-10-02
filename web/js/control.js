$(document).ready(function(){

    $('.dashbrd').hide();

    $(".a-footer").click(function(e) {
        e.preventDefault();
        $(this).prev().toggle();
        if($(this).find('span:first').text()=='Подробнее') {
            $(this).find('span:first').text('Свернуть');
            $(this).find('span:last').find('i').removeClass('fa-arrow-circle-down');
            $(this).find('span:last').find('i').addClass('fa-arrow-circle-up');
            $('.headpan').css('z-index','9999');
        }
        else {
            $(this).find('span:first').text('Подробнее');
            $(this).find('span:last').find('i').removeClass('fa-arrow-circle-up');
            $(this).find('span:last').find('i').addClass('fa-arrow-circle-down');
            $('.headpan').css('z-index','999');
        }
    });

    $('#rentlog-notime').click(function() {
        if($("#rentlog-notime").prop("checked")) {
            $("#rentlog-period").prop("disabled", true);
            $('#rentlog-period option').each(function(){
                $(this).prop("selected", true);
            });
            $("#rentlog-alltime").prop("checked",false);
        }
        else {
            $("#rentlog-period").prop("disabled", false);
            $('#rentlog-period option').each(function(){
                $(this).prop("selected", false);
            });
        }
    });

    $('#rentlog-alltime').click(function() {
        if($("#rentlog-alltime").prop("checked")) {
            //$("#period").prop("disabled", true);
            $('#rentlog-period option').each(function(){
                $(this).prop("selected", true);
            });
            $("#rentlog-notime").prop("checked",false);
        }
        else {
            $("#rentlog-period").prop("disabled", false);
            $('#rentlog-period option').each(function(){
                $(this).prop("selected", false);
            });
        }
    });

    $('#rentlog-allrent').click(function() {
        if($("#rentlog-allrent").prop("checked")) {
            $('#rentlog-renter_id option').each(function(){
                $(this).prop("selected", true);
            });
        }
        else {
            $("#rentlog-renter_id").prop("disabled", false);
            $('#rentlog-renter_id option').each(function(){
                $(this).prop("selected", false);
            });
        }
    });

    $('#rentlog-renter_id').click(function() {
        if($("#rentlog-allrent").prop("checked"))
            $("#rentlog-allrent").prop("checked", false);
    });

    $('#rentlog-period').click(function() {
        if($("#rentlog-alltime").prop("checked"))
            $("#rentlog-alltime").prop("checked", false);
    });

    $('#workreport-allrent').click(function() {
        if($("#workreport-allrent").prop("checked")) {
            $('#workreport-renter_id option').each(function(){
                $(this).prop("selected", true);
            });
        }
        else {
            $("#workreport-renter_id").prop("disabled", false);
            $('#workreport-renter_id option').each(function(){
                $(this).prop("selected", false);
            });
        }
    });

    $('#workreport-renter_id').click(function() {
        if($("#workreport-allrent").prop("checked"))
            $("#workreport-allrent").prop("checked", false);
    });
});