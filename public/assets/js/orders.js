$('document').ready(function() {
    // base_url    = windw.location.origin;
    var base_url = window.location.origin;
    // Owl Carousel


    $("body").on("click", ".update_order_status", function() {
        $("#intial_order_acceptance").hide();
        $("#status_needs_images").hide();
        var sub_attr = $(this).attr("data-suborder_id");
        var curr_status = parseFloat($(this).attr("data-current_status"));
        var newcurstatus = curr_status + 1;
        console.log(newcurstatus);
        // return false;
        order_id = $(this).attr("data-order_id");
        $.ajax({
            type: "GET",
            url: base_url + '/seller/orders/GetOrderStatus',
            success: function(msg) {
                if (msg['status'] == true) {
                    let need_img = 0;
                    li_html = '';
                    $.each(msg['data'], function(ex, status) {
                        let curloopid = status.id;
                        if (curloopid != 2) {
                            if (curloopid < curr_status) {
                                li_html += '<li><del>' + status.name + '</del></li>';
                            } else if (curloopid == curr_status) {
                                li_html += '<li><b>' + status.name + '</b></li>';
                            } else if (curloopid > curr_status) {
                                li_html += '<li><span class="text-muted">' + status.name + '</span></li>';
                            }
                            if (status.pic_to_verify == '1' && curloopid == newcurstatus) {
                                need_img++;
                            }
                            if (curr_status != 1 && curloopid == newcurstatus) {
                                $("#order_action_id").html('Move to ' + status.name + '');
                                $("#new_order_status_name").val(status.name);
                            }
                            if (curr_status == 1) {
                                $("#order_action_id").html('Take Action');
                                $("#new_order_status_name").val(status.name);
                            }
                        }
                    });
                    $("#ListOrderStatus").html(li_html);
                    if (curr_status == 1) {
                        $("#intial_order_acceptance").show();
                    }
                    if (need_img > 0) {
                        $("#status_needs_images").show();
                    }
                    $("#sub_attr_id").val(sub_attr);
                    $("#new_hidden_status_id").val(newcurstatus);
                    $("#curr_order_id").val(order_id);
                    $("#curr_status_id").val(curr_status);
                    $("#UpdateOrderStatus").modal('show');
                } else {
                    $("#intial_order_acceptance").hide();
                    $("#status_needs_images").hide();
                    iziToast.error({
                        timeout: 3000,
                        id: 'error',
                        title: 'Error',
                        message: msg['message'],
                        position: 'topRight',
                        transitionIn: 'fadeInDown'
                    });
                }
            }
        });
    });

    // Order Status Update form
    $('#update_status_form').validate({ // your rules and options,
        rules: {
            "images[]": {
                required: true,
            },
            remarks: {
                required: true,
                minlength: 5,
                maxlength: 30
            }
        },
        messages: {
            "images[]": {
                required: "<font color='red'>Please Upload Images Before Packing</font>",
            },
            remarks: {
                required: "<font color='red'>Please provide Remarks</font>",
                minlength: "<font color='red'>Enter Remarks with minimum 6 charecters</font>"
            }
        },
        submitHandler: function(form) {
            var data = new FormData(form);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: base_url + '/seller/orders/UpdateOrderStatus',
                type: "POST",
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                // beforeSend  : function () {
                //     $('.theme-loader1').show();
                // },
                success: function(result) {
                    if (result['status'] == true) {
                        location.reload();
                        // iziToast.success({
                        //     timeout: 3000,
                        //     id: 'success',
                        //     title: 'Success',
                        //     message: result['message'],
                        //     position: 'bottomRight',
                        //     transitionIn: 'bounceInLeft',
                        // });
                    } else {
                        $('#approval_status').modal('hide');
                        iziToast.error({
                            timeout: 3000,
                            id: 'error',
                            title: 'Error',
                            message: result['message'],
                            position: 'topRight',
                            transitionIn: 'fadeInDown'
                        });
                    }
                }
            });
        }
    });

    $("body").on("click", ".view_status_track_details", function() {
        $("#track_table_id").show();
        $("#show_images_id").hide();
        var sub_order_id = $(this).attr("data-suborder_id");
        var order_id = $(this).attr("data-order_id");
        $.ajax({
            type: "GET",
            url: base_url + '/seller/orders/GetOrderTrack?sub_order_id=' + sub_order_id,
            success: function(msg) {
                if (msg['status'] == true) {
                    console.log(msg['data']);
                    var table_html = '';
                    var sno = 0;
                    $.each(msg['data'], function(ex, track) {
                        sno++;
                        table_html += '<tr>';
                        table_html += '<td>' + sno + '</td>';
                        table_html += '<td>' + track.status_name + '</td>';
                        table_html += '<td>' + track.remarks + '</td>';
                        if (track.images != null)
                            table_html += '<td><button type="button" class="badge badge-info float-right show_status_images" data-images=' +
                            track.images + ' data-status_name="' +
                            track.status_name + '">View</button></td>';
                        else
                            table_html += '<td> - </td>';
                        dt = Date(track.created_at, 'yyyy-MM-dd H:i:s');
                        // let date = Date.parse(dt).toString('yyyy-MM-dd H:i:s');
                        // show_date_format_dt_first(prj_det.prj_start_dt)
                        table_html += '<td>' + dt + '</td>';
                        table_html += '</tr>';
                    });
                    // return false;
                    $("#track_status_data").html(table_html);
                    $("#ViewTrackDetails").modal("show");
                } else {
                    iziToast.error({
                        timeout: 3000,
                        id: 'error',
                        title: 'Error',
                        message: msg['message'],
                        position: 'topRight',
                        transitionIn: 'fadeInDown'
                    });
                }
            }
        });
    });

    $("body").on('click', '.show_status_images', function() {
        var images = $(this).attr("data-images");
        var statusname = $(this).attr("data-status_name");
        let imgArr = images.split("|");
        let img_html = '';

        $.each(imgArr, function(e, img) {
            img_html += '<div class="col-md-4"><img class="img-responsive img-fluid m-2" src="' + base_url + '/' + img + ' " alt="Status Images"></div>';
        });
        $("#status_img_heading").html(statusname);
        $(".display_images").html(img_html);
        //  height="400px" width="400px"
        // $(this).trigger('destroy.owl-carousel');
        // $('.owl-carousel').data('owlCarousel').destroy();
        // $('.owl-carousel').trigger('refresh.owl.carousel');
        // $('.owl-carousel').refresh();
        // $('.owl-carousel').owlCarousel({
        //     items: 1,
        //     loop: true,
        //     margin: 10,
        //     nav: true,
        // });
        $("#track_table_id").hide();
        $("#show_images_id").show();
    });

    $("body").on("click", ".back_to_track", function() {
        $("#track_table_id").show();
        $("#show_images_id").hide();
    });
});