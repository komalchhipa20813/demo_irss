ajax_call();
setInterval(ajax_call,900000);
function ajax_call(){
    $.ajax({
        url: aurl + "/carnival-event/data",
        type: "POST",
        dataType: "JSON",
        success: function(data) {
            $('#total_amount').html(data.total);
            $('#total_nop').html(data.total_nop)
            var html='';
            $.each(data.policy_wise, function(key, value) {
                html+='<tr><th>'+key.toUpperCase()+'</th><td>'+value.total+'</td><td>'+value.nop+'</td></tr>'
            });
            $('.policy_wise_div').html(html)
            var html='';
            $.each(data.agent_wise.motor, function(key, value) {
                html+='<tr><td>'+value.code+'</td><td>'+value.total+'</td><td>'+value.nop+'</td></tr>'
            });
            $('.agent_motor_wise_div').html(html)
            var html='';
            $.each(data.agent_wise.health, function(key, value) {
                html+='<tr><td>'+value.code+'</td><td>'+value.total+'</td><td>'+value.nop+'</td></tr>'
            });
            $('.agent_health_wise_div').html(html)
            var html='';
            $.each(data.agent_wise.sme, function(key, value) {
                html+='<tr><td>'+value.code+'</td><td>'+value.total+'</td><td>'+value.nop+'</td></tr>'
            });
            $('.agent_sme_wise_div').html(html)
        },
        timeout:400000
    });
}

$('.owl-carousel').owlCarousel({
    loop:true,
    margin:0,
    items:1,
    nav:false,
    autoplay:true,
    autoplayTimeout:15000,
})