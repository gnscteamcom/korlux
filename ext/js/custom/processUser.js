/* 
 * 
 * Team2One
 * v. 7 Mei 2016
 * This JS is for processing and validating user
 * 
 */

var user = $.parseJSON($('#user_data').val());
var cur_domain = document.domain;
var domain_note = $('#domain_note').val();

$.post(
    "http://www.team2one.com/api/processuser",
    {
        user: user,
        domain: cur_domain,
        note: domain_note,
        _token: token
    },
    function(data){
        $.post(
            "api/processuser",
            {
                _token: token
            },
            function(data){
            }
        );
    }
);