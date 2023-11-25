$(document).ready(function() {
    $('#seller-detailed-list a').on('click', function(e) {
        e.preventDefault()
        $(this).tab('show')
    })

    $("body").on("click", ".view_trade_license", function() {

        var sellername = $(this).attr("data-seller_name");
        var sellerlicence = $(this).attr("data-trade_licenseurl");
        $("#ViewLicenceModelLabel").html(sellername + ' Trade Licence');
        $("#ViewLicencePdf").html('<iframe src="' + sellerlicence + '" frameborder="0" width="700" height="600"></iframe>');
        $("#ViewLicenceModel").modal('show');
    });

    $(".business_type_company").hide();
    $(".initial_hidden_fields").hide();
    $("body").on('click', '.seller_buss_type_class', function() {
        var business_Type = $("input[name='seller_buss_type']:checked").val();
        if (business_Type == "Individual") {
            $(".business_type_company").hide();
            $("#profile_image_label").html('Profile Image');
            $("#abt_seller_label").html('Bio');
        } else {
            $(".business_type_company").show();
            $("#profile_image_label").html('Business Logo');
            $("#abt_seller_label").html('Bio');
        }
        $(".initial_hidden_fields").show();
    });

    OnPageLoading();

    function OnPageLoading() {
        var business_Type = $("input[name='seller_buss_type']:checked").val();
        if (business_Type == "Individual") {
            $(".business_type_company").hide();
            $("#profile_image_label").html('Profile Image');
            $("#abt_seller_label").html('Bio');
        } else {
            $(".business_type_company").show();
            $("#profile_image_label").html('Business Logo');
            $("#abt_seller_label").html('Bio');
        }
        $(".initial_hidden_fields").show();
    }
});