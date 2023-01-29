$(document).ready(function () {
  //CHECKOUT BTN
  jQuery(".updateUserRecordBtn").click(function () {
    const userid = jQuery(this).attr("data-idGrab");
    const checkin = jQuery(this).attr("data-checkinGrab");
    const breakin = jQuery(this).attr("data-breakIn");
    const totbreakhours = jQuery(this).attr("data-totbreakGrab");
    const checkoutuser = jQuery(this).attr("data-checkoutGrab");
    

    const grabModal = jQuery("#updateUserRecordModal");
    grabModal.find(".modal-body").find(".checkinuser").html(checkin);
    grabModal.find(".modal-body").find(".iduser").html(userid);
    grabModal.find(".modal-body").find(".breakinuser").html(breakin);
    grabModal
      .find(".modal-body")
      .find(".totalbreakhoursuser")
      .html(totbreakhours);
    grabModal.find(".modal-body").find(".checkoutuser").html(checkoutuser);

    console.log("checkin -> ", checkin);
    const mcheckin = moment(checkin, "h:mm:ss A").format("HH:mm");

    
    // Update Fields On Data
    if (checkin) {
      grabModal.find(".modal-body").find("#grabcheckin2").val(mcheckin);
      console.log("mcheckin -> ", mcheckin);
    }

    if (totbreakhours) {
      grabModal.find(".modal-body").find("#grabbreaktime").val(totbreakhours);
    }

    if (checkoutuser) {
      const mcheckout = moment(checkoutuser, "hh:mm:ss a").format("HH:mm");
      grabModal.find(".modal-body").find("#grabcheckout2").val(mcheckout);
    };


    
  });
  //CHECKOUT AJAX
  jQuery("#updateRecordBtn").click(function () {
    const id = jQuery(".iduser").text();
    const checkin = jQuery(".checkinuser").text();
    const breakin = jQuery(".breakinuser").text();
    const breakhours = jQuery(".totalbreakhoursuser").text();
    const checkout = jQuery(".checkoutuser").text();

    const grab_checkin = jQuery(".checkintime-field").val();
    const grab_breakhours = jQuery(".grabbreaktime-field").val();
    const grab_checkout = jQuery(".checkouttime-field").val();
    const grabbreakin = jQuery(".grabbreakin").val();
    const grabbreakout = jQuery(".grabbreakout").val();
    

    let mcheckin;
    let mbreakin;
    let mbreakout;
    let mcheckout;

    if(grabbreakin) {
      mbreakin = moment(grabbreakin, "hh:mm:ss A").format("HH:mm");
    }
    if(grabbreakout) {
      mbreakout = moment(grabbreakout, "hh:mm:ss A").format("HH:mm");
    }
    
    if(grab_checkin) {
      mcheckin = moment(grab_checkin, "HH:mm").format("hh:mm:ss a");
    } else {
      mcheckin = "";
    }
    
    if(grab_checkout) {
       mcheckout = moment(grab_checkout, "HH:mm").format("hh:mm:ss a");
    } else {
       mcheckout = "";
    }
    
    
    // console.log('grab_checkin -> ',mcheckin);
    // console.log('grabbreakin -> ',mbreakin);
    // console.log('grabbreakout -> ',mbreakout);
    // console.log('grab_checkout -> ',mcheckout);

   

   jQuery.ajax({
      type: "POST",
      url: "userfunction/updateUserAttendenceRecord.php",
      data: {
        id: id,
        checkin: checkin,
        breakin: mbreakin,
        breakout: mbreakout,
        breakhours: breakhours,
        checkout: checkout,
        grab_checkin: mcheckin,
        grab_checkout: mcheckout,
      },
      success: function (response) {
        alert(response);
      },
      error: function(jqXHR, textStatus, errorThrown) {
          // console.log(textStatus, errorThrown);
          // alert(`${textStatus, errorThrown}`)
      }
    });
  });

});
