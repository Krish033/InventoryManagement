$(document).ready(function() {
    let RootUrl = $('#txtRootUrl').val();
    let blnEdit=$('#blnEdit').val();
    if((blnEdit=="")||(blnEdit==undefined)){blnEdit=0;}
    //Tab Start
    var currentTab = 0;
    showTab(currentTab);

    function showTab(n) {
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        let title = x[n].getAttribute('data-title');
        if ((title == "") || (title == null) || (title == undefined)) {
            title = "{{$FormData['PageTitle']}}";
        }
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        $('#title').html(title);
        fixStepIndicator(n)

        let getAccount = x[n].getAttribute('data-is-getAccount');
        if ((getAccount == "") || (getAccount == null) || (getAccount == undefined)) {
            getAccount = 0;
        }
        if (getAccount == 1) {
            getFromAccount(x[n].getAttribute('id'));
        }
    }

    function nextPrev(n) {
        var x = document.getElementsByClassName("tab");
        if (n == 1 && !validateForm()) return false;
        x[currentTab].style.display = "none";
        currentTab = currentTab + n;
        if (currentTab >= x.length) {
            //document.getElementById("regForm").submit();
            return false;
        }
        showTab(currentTab);
    }

    function validateForm() {
        /*
                    var x, y, i, valid = true;
                    x = document.getElementsByClassName("tab");
                    y = x[currentTab].getElementsByTagName("input");
                    for (i = 0; i < y.length; i++) {
                        if (y[i].value == "") {
                            y[i].className += " invalid";
                            valid = false;
                        }
                    }
                    if (valid) {
                        document.getElementsByClassName("step")[currentTab].className += " finish";
                    }*/
        return true;
    }

    function fixStepIndicator(n) {
        $('#divStepIndicator').html('');
        var tabs = document.getElementsByClassName("tab");
        for (let i = 0; i < tabs.length; i++) {
            $('#divStepIndicator').append('<span class="step"></span>');
        }

        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        x[n].className += " active";
    }
    $('#prevBtn').click(function() {
        nextPrev(-1);
    });
    $('#nextBtn').click(function() {
        nextPrev(1);
    });
    //Tab End
    const getFromAccount = async (TabID) => {
        let SNO = $('#' + TabID).attr('data-sno');
        let MID = $('#' + TabID).attr('data-mid');
        let Purity = $('#txtInputPurity').val();
        $('#lstAccount' + SNO).select2('destroy');
        $('#lstAccount' + SNO + ' option').remove();
        $('#lstAccount' + SNO).append('<option value="">Select a From Account</option>');
        $.ajax({
            type: "post",
            url: RootUrl+"Gold/Alloying-And-Melting/get/FromAccounts",
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            data: {
                MID: MID,
                Purity: Purity
            },
            dataType: "json",
            error: function(e, x, settings, exception) {
                ajax_errors(e, x, settings, exception);
            },
            complete: function(e, x, settings, exception) {},
            success: function(response) {
                for (let Item of response) {
                    let selected = "";
                    $('#lstAccount' + SNO).append('<option ' + selected + ' value="' + Item.UserID + '">' + Item.UserName + '</option>');
                }
            }
        });
        $('#lstAccount' + SNO).select2();
    }
    const formCalculation = async () => {
        let IPWeight = $('#txtInputWeight').val();
        if (IPWeight == "") {
            IPWeight = 0;
        }
        let IPPurity = $('#txtInputPurity').val();
        if (IPPurity == "") {
            IPPurity = 0;
        }
        let TargetPurity = $('#txtTargetPurity').val();
        if (TargetPurity == "") {
            TargetPurity = 0;
        }
        //Pure Value=Weight*Purity/100
        //Alloying=((IPWEight*IPPurity)/TargetPurity) - IPWeight
        let IPPureValue = parseFloat(parseFloat(IPWeight) * parseFloat(IPPurity) / 100).toFixed(3);


        //let PureValure=parseFloat(parseFloat(TotalWeight)*parseFloat(TargetPurity)/100).toFixed(3);

        let Alloying = parseFloat((parseFloat(IPWeight) * parseFloat(IPPurity)) / parseFloat(TargetPurity) - parseFloat(IPWeight)).toFixed(3);

        var myTable = document.getElementById('tblAlloyStandard');
        var totrows = $('#tblAlloyStandard tbody tr').length;
        let row = 0;
        let TotalQty = 0;
        if (Alloying != 0) {
            for (k = 1; k <= totrows; k++) {
                let RowIndex = myTable.rows[k].cells[0].innerHTML;
                let Percentage = $('#Percentage' + RowIndex).html();
                if (Percentage == "") {
                    Percentage = 0;
                }
                let Qty = parseFloat(parseFloat(Alloying) * parseFloat(Percentage) / 100).toFixed(3);
                if (isNaN(Qty)) {
                    Qty = 0;
                }
                $('#Qty' + RowIndex).html(Qty)
                TotalQty = parseFloat(TotalQty) + parseFloat(Qty);
            }
        }
        let TotalWeight = parseFloat(parseFloat(IPWeight) + parseFloat(TotalQty)).toFixed(3);
        let PureValue = parseFloat((parseFloat(TotalWeight) * parseFloat(TargetPurity)) / 100).toFixed(3)
        $('#txtIPPureValue').val(IPPureValue);
        $('#txtAlloying').val(Alloying);
        $('#tdTotalWeight').html(TotalWeight);
        $('#tdPureValue').html(PureValue);

        if (Alloying < 0) {
            $('.divMetals').show();
            $('.divAlloyStandards').hide();
            $('#lblAlloy').html('Gold');
        } else {
            $('.divMetals').hide();
            $('.divAlloyStandards').show();
            $('#lblAlloy').html('Alloy');
        }
    }
    const getAlloyStandard = async () => {
        $('#tblAlloyStandard tbody tr').remove();
        let ASID = $('#lstAlloyingStandard').val();
        let Alloy = $('#txtAlloying').val();
        if (Alloy == "") {
            Alloy = 0;
        }
        let Purity = $('#txtInputPurity').val();
        if (Purity == "") {
            Purity = 0;
        }
        $.ajax({
            type: "post",
            url: RootUrl+"Gold/Alloying-And-Melting/get/Alloy-Standard",
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            data: {
                ASID: ASID,
                Alloy: Alloy,
                Purity: Purity
            },
            error: function(e, x, settings, exception) {
                ajax_errors(e, x, settings, exception);
            },
            complete: function(e, x, settings, exception) {},
            success: function(response) {
                $('#divAlloyStandards').html(response);
                setTimeout(() => {
                    showTab(currentTab);
                    $('.divTotalAlloying').html(Alloy);
                }, 100);
            }
        });

    }
    const getStages = async () => {
        let DepartmentID = $('#lstDepartment').val();
        $('#lstStage').select2('destroy');
        $('#lstStage option').remove();
        $('#lstStage').append('<option value="">Select a Stage</option>');
        $.ajax({
            type: "post",
            url: RootUrl+"Gold/Alloying-And-Melting/get/Stages",
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            data: {
                DepartmentID: DepartmentID
            },
            dataType: "json",
            error: function(e, x, settings, exception) {
                ajax_errors(e, x, settings, exception);
            },
            complete: function(e, x, settings, exception) {},
            success: function(response) {
                for (let Item of response) {
                    let selected = "";
                    $('#lstStage').append('<option ' + selected + ' value="' + Item.StageID + '">' + Item.Stage + '</option>');
                }
                if ($('#lstStage').val() != "") {
                    getStaffs();
                }
            }
        });
        $('#lstStage').select2();
    }
    const getStaffs = async () => {
        let StageID = $('#lstStage').val();
        $('#lstStaff').select2('destroy');
        $('#lstStaff option').remove();
        $('#lstStaff').append('<option value="">Select a Staff</option>');
        $.ajax({
            type: "post",
            url: RootUrl+"Gold/Alloying-And-Melting/get/Staffs",
            headers: {
                'X-CSRF-Token': $('meta[name=_token]').attr('content')
            },
            data: {
                StageID: StageID
            },
            dataType: "json",
            error: function(e, x, settings, exception) {
                ajax_errors(e, x, settings, exception);
            },
            complete: function(e, x, settings, exception) {},
            success: function(response) {
                for (let Item of response) {
                    let selected = "";
                    if (Item.StaffID == "<?php if($FormData['Edit']==true){ echo $FormData['EditData'][0]->StaffID; } ?>") {
                        selected = "Selected";
                    }
                    $('#lstStaff').append('<option ' + selected + '  value="' + Item.StaffID + '">' + Item.StaffName + '</option>');
                }
            }
        });
        $('#lstStaff').select2();
    }
    const getData = async () => {
        let formData = {};
        let AlloyStandard = [];
        var myTable = document.getElementById('tblAlloyStandard');
        var totrows = $('#tblAlloyStandard tbody tr').length;
        let row = 0;
        for (k = 1; k <= totrows; k++) {
            let tdata = {};
            let RowIndex = myTable.rows[k].cells[0].innerHTML;
            tdata['SNO'] = RowIndex;
            tdata['MID'] = $('#MID' + RowIndex).html();
            tdata['Percentage'] = $('#Percentage' + RowIndex).html();
            tdata['Qty'] = $('#Qty' + RowIndex).html();
            AlloyStandard[AlloyStandard.length] = tdata;
        }
        formData['Date'] = $('#dtpDate').val();
        formData['DepartmentID'] = $('#lstDepartment').val();
        formData['StageID'] = $('#lstStage').val();
        formData['StaffID'] = $('#lstStaff').val();
        formData['IPWeight'] = $('#txtInputWeight').val();
        formData['IPPurity'] = $('#txtInputPurity').val();
        formData['IPPureValue'] = $('#txtIPPureValue').val();
        formData['TargetPurity'] = $('#txtTargetPurity').val();
        formData['Alloying'] = $('#txtAlloying').val();
        if (formData['Alloying'] < 0) {
            formData["AMType"] = "high_to_low";
            formData['ASID'] = $('#lstMetals').val();
            formData['AlloyStandard'] = [{
                SNO: 1,
                MID: $('#lstMetals').val(),
                Percentage: 100,
                Qty: -1 * parseFloat(formData['Alloying']).toFixed(3)
            }];
        } else {
            formData["AMType"] = "low_to_high";
            formData['ASID'] = $('#lstAlloyingStandard').val();
            formData['AlloyStandard'] = AlloyStandard;
        }
        formData['TotalWeight'] = $('#tdTotalWeight').html();
        formData['PureValue'] = $('#tdPureValue').html();
        return formData;
    }
    const FormValidation = async (data) => {
        let status = true;
        if (data.Date == "") {
            $('#dtpDate-err').html('Date is required');
            status = false;
        }
        if (data.DepartmentID == "") {
            $('#lstDepartment-err').html('Department is required');
            status = false;
        }
        if (data.StageID == "") {
            $('#lstStage-err').html('Stage is required');
            status = false;
        }
        if (data.StaffID == "") {
            $('#lstStaff-err').html('Staff is required');
            status = false;
        }
        if (data.IPPurity == "") {
            $('#txtInputPurity-err').html('Input Purity is required');
            status = false;
        } else if (!$.isNumeric(data.IPPurity)) {
            $('#txtInputPurity-err').html('Input Purity is not numeric value');
            status = false;
        } else if (data.IPPurity < 0) {
            $('#txtInputPurity-err').html('Input Purity must be greater then 0');
            status = false;
        } else if (data.IPPurity > 100) {
            $('#txtInputPurity-err').html('Input Purity is not greater then 100');
            status = false;
        }

        if (data.IPWeight == "") {
            $('#txtInputPurity-err').html('Input Weight is required');
            status = false;
        } else if (!$.isNumeric(data.IPWeight)) {
            $('#txtInputWeight-err').html('Input Weight is not numeric value');
            status = false;
        } else if (data.IPWeight < 0) {
            $('#txtInputWeight-err').html('Input Weight must be greater then 0');
            status = false;
        }

        if (data.IPPureValue == "") {
            $('#txtIPPureValue-err').html('Input Pure Value is required');
            status = false;
        } else if (!$.isNumeric(data.IPPureValue)) {
            $('#txtIPPureValue-err').html('Input Pure Value is not numeric value');
            status = false;
        } else if (data.IPPureValue < 0) {
            $('#txtIPPureValue-err').html('Input Pure Value must be greater then 0');
            status = false;
        }

        if (data.TargetPurity == "") {
            $('#txtTargetPurity-err').html('Target Purity is required');
            status = false;
        } else if (!$.isNumeric(data.TargetPurity)) {
            $('#txtTargetPurity-err').html('Target Purity is not numeric value');
            status = false;
        } else if (data.TargetPurity < 0) {
            $('#txtTargetPurity-err').html('Target Purity must be greater then 0');
            status = false;
        } else if (data.TargetPurity > 100) {
            $('#txtTargetPurity-err').html('Target Purity is not greater then 100');
            status = false;
        }

        if (data.Alloying == "") {
            $('#txtAlloying-err').html('Alloying is required');
            status = false;
        } else if (!$.isNumeric(data.Alloying)) {
            $('#txtAlloying-err').html('Alloying is not numeric value');
            status = false;
        }

        if (data.ASID == "") {
            $('#lstAlloyingStandard-err').html('Alloy Standard is required');
            status = false;
        }
        if (data.TotalWeight == "") {
            $('#tdTotalWeight-err').html('Total Weight is required');
            status = false;
        } else if (!$.isNumeric(data.TotalWeight)) {
            $('#tdTotalWeight-err').html('Total Weight is not numeric value');
            status = false;
        } else if (data.TotalWeight < 0) {
            $('#tdTotalWeight-err').html('Total Weight is required');
            status = false;
        }

        if (data.PureValue == "") {
            $('#tdPureValue-err').html('Pure Value is required');
            status = false;
        } else if (!$.isNumeric(data.PureValue)) {
            $('#tdPureValue-err').html('Pure Value is not numeric value');
            status = false;
        } else if (data.PureValue < 0) {
            $('#tdPureValue-err').html('Pure Value must be greater then equal 0');
            status = false;
        }
        if (data.Alloying < 0) {
            if (data.ASID == "") {
                $('#lstMetals-err').html('Metal is required');
                status = false;
            }
        } else {
            if (data.AlloyStandard == "") {
                toastr.error("Alloy Standards are required.", "Warning", {
                    positionClass: "toast-top-right",
                    containerId: "toast-top-right",
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    progressBar: !0
                })
                status = false;
            } else if (data.AlloyStandard.length <= 0) {
                toastr.error("Alloy Standards are required.", "Warning", {
                    positionClass: "toast-top-right",
                    containerId: "toast-top-right",
                    showMethod: "slideDown",
                    hideMethod: "slideUp",
                    progressBar: !0
                })
                status = false;
            } else {
                for (let i = 0; i < data.AlloyStandard.length; i++) {
                    let Item = data.AlloyStandard[i];
                    if (Item.MID == "") {
                        $('#MID' + Item.SNO + '-err').html('Metal is required');
                        status = false;
                    }
                    if (Item.Percentage == "") {
                        $('#Percentage' + Item.SNO + '-err').html('Percentage is required');
                        status = false;
                    } else if (!$.isNumeric(Item.Percentage)) {
                        $('#Percentage' + Item.SNO + '-err').html('Percentage is not numeric value');
                        status = false;
                    } else if (Item.Percentage < 0) {
                        $('#Percentage' + Item.SNO + '-err').html('Percentage must be greater then 0');
                        status = false;
                    } else if (Item.Percentage > 100) {
                        $('#Percentage' + Item.SNO + '-err').html('Percentage is not greater then 100');
                        status = false;
                    }
                    if (Item.Qty == "") {
                        $('#Qty' + Item.SNO + '-err').html('Qty is required');
                        status = false;
                    } else if (!$.isNumeric(Item.Qty)) {
                        $('#Qty' + Item.SNO + '-err').html('Qty is not numeric value');
                        status = false;
                    }
                }
            }
        }
        return status;
    }
    $('#txtInputWeight').keyup(function() {
        formCalculation();
    });
    $('#txtInputPurity').keyup(function() {
        formCalculation();
    });
    $('#txtTargetPurity').keyup(function() {
        formCalculation();
    });
    $('#txtInputWeight').change(function() {
        formCalculation();
    });
    $('#txtInputPurity').change(function() {
        formCalculation();
    });
    $('#txtTargetPurity').change(function() {
        formCalculation();
    });
    $('#lstAlloyingStandard').change(function() {
        getAlloyStandard();
    });
    $('#lstDepartment').change(function() {
        getStages();
    });
    $('#lstStage').change(function() {
        getStaffs();
    });

    $('#btnSave').click(async function() {
        swal({
                title: "Are you sure?",
                text: "You want @if($FormData['Edit']==true)Update @else Save @endif this Mapping!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, @if($FormData['Edit']==true)Update @else Save @endif it!",
                closeOnConfirm: false
            },
            async function() {
                swal.close();
                btn_Loading($('#btnSave'));
                let posturl = RootUrl+"Gold/Alloying-And-Melting/Create";
                let formData = await getData();
                if(blnEdit==1){
                    posturl = RootUrl+"Gold/Alloying-And-Melting/{{$FormData['EditData'][0]->AAMID}}";
                }
                let status = await FormValidation(formData);
                if (status == true) {
                    $.ajax({
                        type: "post",
                        url: posturl,
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        data: formData,
                        dataType: "json",
                        error: function(e, x, settings, exception) {
                            ajax_errors(e, x, settings, exception);
                        },
                        complete: function(e, x, settings, exception) {
                            btn_reset($('#btnSave'));
                        },
                        success: function(response) {
                            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                            if (response.status == true) {
                                swal({
                                    title: "SUCCESS",
                                    text: response.message,
                                    type: "success",
                                    showCancelButton: false,
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "Okay",
                                    closeOnConfirm: false
                                }, function() {
                                    if(blnEdit==1){
                                        window.location.replace(RootUrl+"Gold/Alloying-And-Melting");
                                    }else{
                                        window.location.reload();
                                    }
                                });

                            } else {
                                toastr.error(response.message, "Failed", {
                                    positionClass: "toast-top-right",
                                    containerId: "toast-top-right",
                                    showMethod: "slideDown",
                                    hideMethod: "slideUp",
                                    progressBar: !0
                                })
                                if (response['errors'] != undefined) {
                                    $('.errors').html('');
                                    $.each(response['errors'], function(KeyName, KeyValue) {
                                        var key = KeyName;
                                        if (key == "Date") {
                                            $('#dtpDate-err').html(KeyValue);
                                        }
                                        if (key == "DepartmentID") {
                                            $('#lstDepartment-err').html(KeyValue);
                                        }
                                        if (key == "StageID") {
                                            $('#lstStage-err').html(KeyValue);
                                        }
                                        if (key == "StaffID") {
                                            $('#lstStaff-err').html(KeyValue);
                                        }
                                        if (key == "IPWeight") {
                                            $('#txtInputWeight-err').html(KeyValue);
                                        }
                                        if (key == "IPPurity") {
                                            $('#txtInputPurity-err').html(KeyValue);
                                        }
                                        if (key == "IPPureValue") {
                                            $('#txtIPPureValue-err').html(KeyValue);
                                        }
                                        if (key == "TargetPurity") {
                                            $('#txtTargetPurity-err').html(KeyValue);
                                        }
                                        if (key == "Alloying") {
                                            $('#txtAlloying-err').html(KeyValue);
                                        }

                                        if (key == "TotalWeight") {
                                            $('#tdTotalWeight-err').html(KeyValue);
                                        }
                                        if (key == "PureValue") {
                                            $('#tdPureValue-err').html(KeyValue);
                                        }
                                        if (formData['Alloying'] < 0) {
                                            if (key == "ASID") {
                                                $('#lstMetals-err').html(KeyValue);
                                            }
                                        } else {
                                            if (key == "ASID") {
                                                $('#lstAlloyingStandard-err').html(KeyValue);
                                            }
                                            if (key == "AlloyStandard") {
                                                if (KeyValue.msg != undefined) {
                                                    if (KeyValue.msg != "") {
                                                        toastr.error(KeyValue.msg, "Failed", {
                                                            positionClass: "toast-top-right",
                                                            containerId: "toast-top-right",
                                                            showMethod: "slideDown",
                                                            hideMethod: "slideUp",
                                                            progressBar: !0
                                                        })
                                                    }
                                                }
                                                if (KeyValue.err != undefined) {
                                                    for (let Err of KeyValue.err) {
                                                        let SNO = Err.SNO;
                                                        $.each(Err.error, function(KeyName1, KeyValue1) {
                                                            if (KeyName1 == "MID") {
                                                                $('#MID' + SNO + '-err').html(KeyValue1);
                                                            }
                                                            if (KeyName1 == "Percentage") {
                                                                $('#Percentage' + SNO + '-err').html(KeyValue1);
                                                            }
                                                            if (KeyName1 == "Qty") {
                                                                $('#Qty' + SNO + '-err').html(KeyValue1);
                                                            }

                                                        });
                                                    }
                                                }
                                            }
                                        }
                                    });
                                }
                            }
                        }
                    });
                } else {
                    btn_reset($('#btnSave'));
                }
            });
    });
});