function loaddownload_report(page) {


    var limit = $('#limit').val(),
            search = $('#search').val(),
            status = $('#status').val(),
            datefrom = $('#datefrom').val(),
            dateto = $('#dateto').val(),
            html = '<table class="table table-striped table-bordered" id="group-list"><thead><tr><th class="hidden-phone">Time</th><th class="hidden-phone">TransactionID</th><th class="hidden-phone">App ID</th><th class="hidden-phone">Ad Network</th><th class="hidden-phone">Phone Brand</th><th class="hidden-phone">Phone Model</th><th class="hidden-phone">OS</th><th class="hidden-phone">Status</th></tr></thead><tbody id="data-table">';

    var selectdate = $("#select-date").val();
    var adnetwork = $("#ad-network").val();

    //alert(selectdate);
    if (adnetwork == 0)
    {
        jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please Select AdNetwork</p>");
        return false;
    }
    if (selectdate == 0)
    {
        jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please Select Day Type</p>");
        return false;
    }
    else if (selectdate == 2 && datefrom == '' && dateto == '')
    {
        jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please Select Date Range</p>");
        return false;
    }
    if (selectdate == 1 && status == '')
    {
        jQuery('#table-append').html("<p style='width:100%;font-size:20px; margin:0 auto; text-align:left;color:red'>Please Select Status</p>");
        return false;
    }
    var base_url = window.location.origin;
    if (selectdate == 1)
    {
        if (page == 1)
        {
            jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="' + base_url + '/cms-tools-ads/assets/images/loader.gif"/></div>');
        } else {
            jQuery('#paging').html('<button  class="btn_green" type="button" style="background-color:#35aa47;color:#fff">Data Loading...........</button>');
        }
    } else {
        jQuery('#table-append').html('<div class="loader-report"><img class="loader-report-img" src="' + base_url + '/cms-tools-ads/assets/images/loader.gif"/></div>');
    }
    jQuery.ajax({
        url: domain + "campaign_manager/download_report/loaddownload_report",
        dataType: "json",
        type: "POST",
        data: {
            'limit': limit,
            'page': page,
            'search': search,
            'status': status,
            'datefrom': datefrom,
            'dateto': dateto,
            'selectdate': selectdate,
            'adnetwork': adnetwork,
            'page_no': page
        },
        //beforeSend: progress_jenis_user_list,
        success: function (data) {
            //alert(data.)
            if (selectdate == 1)
            {
                if (page == 1)
                {
                    jQuery('#table-append').html('');
                }

                var totalres = '<div id="info">Showing ' + (500 * page) + ' of total <span id="red">' + data.total_records + '</span> records found.</div>';
            } else {
                jQuery('#table-append').html('');
                var totalres = '<div id="info">Total <span id="red">' + data.total + '</span> records found.</div>';
            }
            var result = '';
            if (data.count) {
                for (var i = 0; i < data.rows.length; i++) {
                    result += '<tr class="odd gradeX"><td>' + data.rows[i]['server_time'] + '</td><td>' + data.rows[i]['id'] + '</td><td>' + data.rows[i]['appid'] + '</td><td>' + data.rows[i]['adnetwork'] + '</td><td title="'+data.rows[i]['user_agent']+'">' + data.rows[i]['phone_brand'] + '</td><td title="'+data.rows[i]['user_agent']+'">' + data.rows[i]['phone_model'] + '</td><td title="'+data.rows[i]['user_agent']+'">' + data.rows[i]['os'] + '</td><td>' + data.rows[i]['status'] + '</td></tr>';
                }

            } else {
                totalres = '';
                result += '<tr style="border:1px solid black;"><td colspan="8" align="center" ><div id="paging" style="text-align:center;"><b>No data found</b></div></div></td></tr>';
            }
            if (selectdate == 1)
            {
                var paging = '';
                /*if (data.count)
                {*/
                    if(data.total_pages>page)
                    var paging = '<tr id="load_more" style="border:1px solid black;"><td colspan="8" align="center" ><div  style="text-align:center;" ><a style="cursor:pointer"  onclick="loaddownload_report(' + data.next_page + ')"><div id="paging"><button  class="btn_green" type="button" style="background-color:#35aa47;color:#fff">Load More</button></div></a></div></td></tr>';
                //}
                    if (page == 1)
                {
                    jQuery('#table-append').append(totalres + html + result + paging + '</tbody></table>');

                } else {
                    //alert();
                    //$('#table-append').append(result+paging+'</tbody></table>');
                    // var paging = '<div id="load_more" style="text-align:center;"><a onclick="loaddownload_report('+data.next_page+')"><div id="paging">Load More</div></a></div>';
                    jQuery('#load_more').replaceWith(result + paging + '</tbody></table>');
                    jQuery('#info').replaceWith(totalres);
                }
            } else {
                var paging = '<div id="paging">' + data.pagination + '</div>';
                $('#table-append').append(totalres + html + result + '</tbody></table>' + paging);
                Main.tableSorting();
            }

        }
    });
}

function showhidedates(opt) {

    if (opt == '1')
    {
        $(".daterange1").addClass('hidden');
        $(".daterange2").addClass('hidden');
        $(".daterange3").addClass('hidden');
        $(".daterange4").addClass('hidden');
        //$(".daterange5").addClass('hidden');
        $(".daterange6").addClass('hidden');
        $(".daterange7").addClass('hidden');
        $("#ad-network").val('');
    }
    else if (opt == '2')
    {
        $(".daterange1").removeClass('hidden');
        $(".daterange2").removeClass('hidden');
        $(".daterange3").removeClass('hidden');
        $(".daterange4").removeClass('hidden');
        $(".daterange5").removeClass('hidden');
        $(".daterange6").removeClass('hidden');
        $(".daterange7").removeClass('hidden');
    }
    else
    {
        $(".daterange1").addClass('hidden');
        $(".daterange2").addClass('hidden');
        $(".daterange3").addClass('hidden');
        $(".daterange4").addClass('hidden');
        $(".daterange7").addClass('hidden');
        //$(".daterange5").addClass('hidden');
        $(".daterange6").addClass('hidden');
        $("#ad-network").val('');
    }

}

function checkDateEntered() {

    var selectdate = $("#select-date").val();
    var adnetwork = $("#ad-network").val();
    var status=$("#status").val();
    if (adnetwork == 0)
    {
        alert("Please Select Ad Nework.");
        return false;
    }else if(status=='')
    {
        alert("Status is required.");
            return false;
    }
    else if (selectdate == 0)
    {
        alert("Please Select Day Type.");
        return false;
    }
    else if (selectdate == 2)
    {
        var datefrom = $('#datefrom').val(),
                dateto = $('#dateto').val();
        if (datefrom == '' || dateto == '')
        {
            alert("First select dates i.e Date from and Date to, for desired period then export.");
            return false;
        }
        else
        {
            $('#reprt_sbmt').submit();
        }
    }
    
    else
    {
        $('#reprt_sbmt').submit();
    }

}

