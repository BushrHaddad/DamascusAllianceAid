$(document).ready(function () {

    if (!window.CRM.currentActive) {
        $("#family-deactivated").removeClass("hide");
    }

    $.ajax({
        url: window.CRM.root + "/api/family/" + window.CRM.currentFamily + "/nav",
        encode: true,
        dataType: 'json'
    }).done(function (data) {
        if (data["PreFamilyId"]) {
            $("#lastFamily").attr("href", window.CRM.root + "/v2/family/" + data["PreFamilyId"]);
        } else {
            $("#lastFamily").addClass("hidden");
        }
        if (data["NextFamilyId"]) {
            $("#nextFamily").attr("href", window.CRM.root + "/v2/family/" + data["NextFamilyId"]);
        } else {
            $("#nextFamily").addClass("hidden");
        }
    });

    let masterFamilyProperties = {};
    let selectedFamilyProperties = []
    window.CRM.APIRequest({
        path: 'people/properties/family',
    }).done(function(data) {
        masterFamilyProperties = data;

        window.CRM.APIRequest({
            path: 'people/properties/family/'+ window.CRM.currentFamily,
        }).done(function(data) {
            if (masterFamilyProperties.length > data.length) {
                $("#add-family-property").removeClass("hidden");
            }

            $("#family-property-loading").addClass("hidden");
            if (data.length == 0) {
                $("#family-property-no-data").removeClass("hidden");
            } else {
                $("#family-property-table").removeClass("hidden");
                $.each(data, function (key, prop) {
                    let propId = prop.id;
                    let editIcon = "";
                    let deleteIcon = "";
                    let propName = prop.name;
                    let propVal = prop.value;
                    selectedFamilyProperties.push(propId);
                    if (prop.allowEdit) {
                        editIcon = "<a href='"+ window.CRM.root  +"/PropertyAssign.php?FamilyID="+ window.CRM.currentFamily +"&PropertyID=" + propId +"'><button type='button' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i></button></a>";
                    }
                    if (prop.allowDelete) {
                        deleteIcon = "<div class='btn btn-xs btn-danger delete-property' data-property-id='" + propId +"' data-property-name='"+propName+"'><i class='fa fa-trash'></i></div>";
                    }

                    $('#family-property-table tr:last').after('<tr><td>' + deleteIcon + " " + editIcon  + '</td><td>' + propName + '</td><td>' + propVal + '</td></tr>');
                });
                $(".delete-property").click(function (){
                    let propId = $(this).attr("data-property-id");
                    bootbox.confirm({
                        title: i18next.t("Family Property Unassignment"),
                        message: i18next.t("Do you want to remove") + " " + $(this).attr("data-property-name") + " " +  "property" ,
                        locale: window.CRM.locale,
                        callback: function (result) {
                            if(result) {
                                window.CRM.APIRequest({
                                    path: 'people/properties/family/'+ window.CRM.currentFamily+"/"+ propId,
                                    method: 'DELETE',
                                }).done(function(data) {
                                    location.reload();
                                });
                            }
                        }
                    });
                });
            }
        });
    });

    $("#add-family-property").click(function (){
        let inputOptions = [];
        $.each(masterFamilyProperties, function (index, masterProp){
            if ($.inArray(masterProp.ProId, selectedFamilyProperties) == -1){
                inputOptions.push({text: masterProp.ProName, value: masterProp.ProId})
            }
        });
        bootbox.prompt({
            title: i18next.t("Assign a New Property"),
            locale: window.CRM.locale,
            inputType: 'select',
            inputOptions: inputOptions,
            callback: function (result) {
                window.CRM.APIRequest({
                    path: 'people/properties/family/'+ window.CRM.currentFamily+"/"+result,
                    method: 'POST',
                }).done(function(data) {
                    location.reload();
                });
            }
        });
    });

    var dataTableConfig = {
        ajax: {
            url: window.CRM.root + "/api/payments/family/"+ window.CRM.currentFamily +"/list",
            dataSrc: "data"
        },
        columns: [
            {
                width: '15px',
                sortable: false,
                title: i18next.t('Edit'),
                data: 'GroupKey',
                render: function (data, type, row) {
                    return '<a class="btn btn-default" href="'+window.CRM.root+'/PledgeEditor.php?GroupKey='+ row.GroupKey + '&amp;linkBack=v2/family/'+window.CRM.currentFamily+'"><i class="fa fa-pencil bg-info"></i></a>';
                },
                searchable: false
            },
            {
                width: '15px',
                sortable: false,
                title: i18next.t('Delete'),
                data: 'GroupKey',
                render: function (data, type, row) {
                    return '<a class="btn btn-default" href="'+window.CRM.root+'/PledgeDelete.php?GroupKey='+ row.GroupKey + '&amp;linkBack=v2/family/'+window.CRM.currentFamily+'"><i class="fa fa-trash bg-red"></i></a>';
                },
                searchable: false
            },
            {
                title: i18next.t('Pledge or Payment'),
                data: 'PledgeOrPayment'
            },
            {
                title: i18next.t('Fund'),
                data: 'Fund'
            },
            {
                title: i18next.t('Fiscal Year'),
                data: 'FormattedFY'
            },
            {
                title: i18next.t('Date'),
                type: 'date',
                data: 'Date'
            },
            {
                title: i18next.t('Amount'),
                type: 'num',
                data: 'Amount'
            },
            {
                title: i18next.t('NonDeductible'),
                type: 'num',
                data: 'Nondeductible'
            },
            {
                title: i18next.t('Schedule'),
                data: 'Schedule'
            },
            {
                title: i18next.t('Method'),
                data: 'Method'
            },
            {
                title: i18next.t('Comment'),
                data: 'Comment'
            },
            {
                title: i18next.t('Date Updated'),
                type: 'date',
                data: 'DateLastEdited'
            },
            {
                title: i18next.t('Updated By'),
                data: 'EditedBy'
            }
        ],
        order: [[5, "asc"]]
    };
    $.extend(dataTableConfig, window.CRM.plugin.dataTable);
    $("#pledge-payment-v2-table").DataTable(dataTableConfig);

    $("#onlineVerify").click(function () {
        window.CRM.APIRequest({
            method : 'POST',
            path: 'family/' + window.CRM.currentFamily + '/verify',
        }).done(function () {
            $('#confirm-verify').modal('hide');
            showGlobalMessage(i18next.t("Verification email sent"), "success")
        });
    });

    $("#verifyNow").click(function () {
        window.CRM.APIRequest({
            method: 'POST',
            path: 'family/' + window.CRM.currentFamily + '/verify/now',
        }).done(function () {
            $('#confirm-verify').modal('hide');
            showGlobalMessage(i18next.t("Verification recorded"), "success")
        });
    });

    $("#verifyURL").click(function () {
        window.CRM.APIRequest({
            path: 'family/' + window.CRM.currentFamily + '/verify/url',
        }).done(function (data) {
            $('#confirm-verify').modal('hide');
            bootbox.alert({
                title: i18next.t("Verification URL"),
                message: "<a href='"+data.url+"'>"+data.url+"</a>"
            });
        });
    });


    $("#verifyDownloadPDF").click(function () {
        window.open(window.CRM.root + '/Reports/ConfirmReport.php?familyId=' + window.CRM.currentFamily, '_blank');
        $('#confirm-verify').modal('hide');
    });

    $("#AddFamilyToCart").click(function () {
        window.CRM.cart.addFamily($(this).data("familyid"));
    });


    // Photos

    $("#deletePhoto").click(function () {
        $.ajax({
            type: "POST",
            url: window.CRM.root + "/api/family/" + window.CRM.currentFamily + "/photo",
            encode: true,
            dataType: 'json',
            data: {
                "_METHOD": "DELETE"
            }
        }).done(function (data) {
            location.reload();
        });
    });

    $("#view-larger-image-btn").click(function () {
        bootbox.alert({
            title: i18next.t('Family Photo'),
            message: '<img class="img-rounded img-responsive center-block" src="' + window.CRM.root + '/api/family/' + window.CRM.currentFamily + '/photo" />',
            backdrop: true
        });
    });


    $("#activateDeactivate").click(function () {
        popupTitle = (window.CRM.currentActive == true ? i18next.t('Confirm Deactivation') : i18next.t("Confirm Activation" ));
        if (window.CRM.currentActive == true) {
            popupMessage = i18next.t("Please confirm deactivation of family") +  ': '  + window.CRM.currentFamilyName;
        }
        else {
            popupMessage = i18next.t('Please confirm activation of family') + ': ' + window.CRM.currentFamilyName;
        }

        bootbox.confirm({
            title: popupTitle,
            message: '<p style="color: red">' + popupMessage + '</p>',
            callback: function (result) {
                if (result) {
                    alert("RESULT"+result);
                    window.CRM.APIRequest({
                        method: "POST",
                        path: "families/" + window.CRM.currentFamily + "/activate/" + !window.CRM.currentActive
                    }).done(function (data) {
                        if (data.success == true) {
                            window.location.href = window.CRM.root + "/v2/family/" + window.CRM.currentFamily;
                        }
                    });
                }
            }
        });
    });

    $("#ShowPledges").click(function () {
        updateUserSetting("finance.show.pledges", $("#ShowPledges").prop("checked") ? "true" : "false");
    });

    $("#ShowPayments").click(function () {
        updateUserSetting("finance.show.payments", $("#ShowPayments").prop("checked") ? "true" : "false");
    });

    $("#ShowSinceDate").change(function () {
        updateUserSetting("finance.show.since", $("#ShowSinceDate").val());
    });

    function updateUserSetting(setting, value){
        window.CRM.APIRequest({
            method: "POST",
            path: "/user/"+ window.CRM.userId +"/setting/"+ setting,
            dataType: 'json',
            data: JSON.stringify({ "value":  value})
        }).done(function () {
            //TODO NOT WORKING $("#pledge-payment-table").DataTable().ajax.reload();
            window.location.reload();
        });
    }

    if (window.CRM.plugin.mailchimp) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: window.CRM.root + '/api/mailchimp/family/' + window.CRM.currentFamily,
            success: function (data, status, xmlHttpReq) {
                for (emailData of data) {
                    let htmlVal = "";
                    let eamilMD5 = emailData["emailMD5"];
                    for (list of emailData["list"]) {
                        let listName = list["name"];
                        let listStatus = list["status"];
                        if (listStatus != 404) {
                            let listOpenRate = list["stats"]["avg_open_rate"]*100;
                            htmlVal = htmlVal + listName + " (" + listStatus + ") - " + listOpenRate + "% "+  i18next.t("open rate");
                        }
                    }
                    if (htmlVal === "") {
                        htmlVal = i18next.t("Not Subscribed ");
                    }
                    $('#'+ eamilMD5).html(htmlVal);
                }
            }
        });
    }


/*******************************************************************************
*
* Author: Bushr Haddad
* Task: Local master table
* Description: New js code added to adjust year option and configure the local master table.
* Date: 25-Feb-2021
* Completed: 02-Mar-2021
*
******************************************************************************/
var table;
var team_options, bag_options, sup_options, visiting_options, cash_options;
var team_dic, bag_dic, sup_dic, visiting_dic, cash_dic;

    function _parse(obj) {
        parsed = [];
        for (index = 0; index < obj.length; index++) {
            parsed.push({
                "value": obj[index]['name'],
                "display": obj[index]['name']
                // "value_id": obj[index]['id']
            })

        }
        return parsed;
    }

    function _dic(obj) {
        parsed = {};
        for (index = 0; index < obj.length; index++) {
            parsed[obj[index]['name']] = obj[index]['id'];
        }
        return parsed;
    }

    $.ajax({

        url: "/churchcrm/PostRedirect.php",
        type: "POST",
        // datatype: "text",
        data: {
            post_name: "get_vars",
        },

        success: function(response) {
            var json = JSON.parse(response);
            team_options = _parse(json['all_teams']);
            bag_options = _parse(json['all_bags']);
            sup_options = _parse(json['all_suppliments']);
            visiting_options = _parse(json['all_visitings']);
            cash_options = _parse(json['all_cash']);

            team_dic = _dic(json['all_teams']);
            bag_dic = _dic(json['all_bags']);
            sup_dic = _dic(json['all_suppliments']);
            visiting_dic = _dic(json['all_visitings']);
            cash_dic = _dic(json['all_cash']);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error on get Ajax request ");
        }
    });

    $("#year_status").change(function() {

        var year_value = $("#year_status").val();
        $.ajax({
            url: "/churchcrm/PostRedirect.php",
            type: "POST",
            data: {
                year_id: year_value,
                post_name: "local_master",
                family_id: window.CRM.currentFamily
            },

            success: function(obj) {
                table = $('#example').DataTable()
                destroyTable();
                var json = JSON.parse(obj);

                table = $('#example').DataTable({
                    destroy: true,
                    "bSort": false,
                    // responsive: true,
                    data: json,
                    //  dataType: 'json',    
                    columns: [{
                            data: "found",
                            "visible": false,
                        },
                        {
                            data: "month_id",
                            "visible": false,
                        },
                        {
                            data: "month_name"
                        },
                        {
                            data: "visiting_name"
                        },
                        {
                            data: "team_name"
                        },
                        {
                            data: "bag_name"
                        },
                        {
                            data: "cash_name"
                        },
                        {
                            data: "sup_name"
                        }

                    ]
                });


                table.MakeCellsEditable({
                    "onUpdate": myCallbackFunction,
                    "inputCss": 'my-input-class',
                    "columns": [0, 1, 2, 3, 4, 5, 6, 7],
                    "confirmationButton": { // could also be true
                        "confirmCss": 'my-confirm-class',
                        "cancelCss": 'my-cancel-class'
                    },
                    "inputTypes": [{
                            "column": 0,
                            "type": "text",
                            "options": null
                        },
                        {
                            "column": 1,
                            "type": "text",
                            "options": null
                        },
                        {
                            "column": 2,
                            "type": "text",
                        },
                        {
                            "column": 3,
                            "type": "list",
                            "options": visiting_options
                        },
                        {
                            "column": 4,
                            "type": "list",
                            "options": team_options
                        },
                        {
                            "column": 5,
                            "type": "list",
                            "options": bag_options
                        },
                        {
                            "column": 6,
                            "type": "list",
                            "options": cash_options
                        },
                        {
                            "column": 7,
                            "type": "list",
                            "options": sup_options
                        },
                        // Nothing specified for column 3 so it will default to text

                    ]
                });

            }

        });
    });

    function myCallbackFunction(updatedCell, updatedRow, oldValue) {
        $.ajax({

            url: "/churchcrm/PostRedirect.php",
            type: "POST",
            // datatype: "text",
            data: {
                post_name: "edit_local_master",
                family_id: window.CRM.currentFamily,
                found: updatedRow.data().found,
                month_id: updatedRow.data().month_id,
                year_id: $("#year_status").val(),
                visited_id: visiting_dic[updatedRow.data().visiting_name],
                team_id: team_dic[updatedRow.data().team_name],
                bag_id: bag_dic[updatedRow.data().bag_name],
                cash_id: cash_dic[updatedRow.data().cash_name],
                sup_id: sup_dic[updatedRow.data().sup_name]
            },

            success: function(response) {
                updatedRow.data().found = true;
                console.log('Edited Correctly');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });

    }

    function destroyTable() {
        if ($.fn.DataTable.isDataTable('#example')) {
            table.destroy();
            table.MakeCellsEditable("destroy");
        }
    }

});
