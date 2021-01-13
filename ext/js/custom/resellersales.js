$('#reseller').change(function() {
  let selected_reseller = $("#reseller option:selected")

  $("#nama_reseller").val(selected_reseller.attr('data-nama-depan'))
  $("#username_reseller").val(selected_reseller.attr('data-username'))
  $("#status_reseller").val(selected_reseller.attr('data-status-reseller'))
  $("#status_id").val(selected_reseller.attr('data-status-id'))
  $("#hp_reseller").val(selected_reseller.attr('data-hp'))
  $("#email_reseller").val(selected_reseller.attr('data-email'))

  $.post(
    "/api/destroymanualsales",
    {
      _token: token
    },
    function (data) {
      $('#product_list').empty();
      $('#total_weight').val(0)
      $('#total_biaya_kirim').val(0)
      $('#biaya_kirim_text').val('Rp. 0')
    }
  );

})
