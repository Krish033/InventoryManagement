$(document).ready(function(){
    let RootUrl=$('#txtRootUrl').val();
    const validateCountry=async()=>{
        let status=true;
        $('.New-Country-err').html('');
        let ShortName=$('#txtmShortName').val();
        let CountryName=$('#txtmCountryName').val();
        let CallingCode=$('#txtmCallingCode').val();
        let PhoneLength=$('#txtmPhoneLength').val();
        
        if(ShortName==""){
            $('#txtmShortName-err').html('Short Name is required');status=false;
        }else if(ShortName.length<2){
            $('#txtmShortName-err').html('Short Name must be atleast 3 characters');status=false;
        }else if(ShortName.length>6){
            $('#txtmShortName-err').html('Short Name may not be greater than 6 characters');status=false;
        }
        if(CountryName==""){
            $('#txtmCountryName-err').html('Country Name is required');status=false;
        }else if(CountryName.length<3){
            $('#txtmCountryName-err').html('Country Name must be atleast 3 characters');status=false;
        }else if(CountryName.length>100){
            $('#txtmCountryName-err').html('Country Name may not be greater than 100 characters');status=false;
        }
        if(CallingCode==""){
            $('#txtmCallingCode-err').html('Calling Code is required');status=false;
        }else if($.isNumeric(CallingCode)==false){
            $('#txtmCallingCode-err').html('Calling Code must be a number');status=false;
        }else if(CallingCode.length<1){
            $('#txtmCallingCode-err').html('Calling Code must be atleast 1 digits');status=false;
        }else if(CallingCode.length>10){
            $('#txtmCallingCode-err').html('Calling Code may not be greater than 10 digits');status=false;
        }
        if(PhoneLength==""){
            $('#txtmPhoneLength-err').html('Phone Length is required');status=false;
        }else if($.isNumeric(PhoneLength)==false){
            $('#txtmPhoneLength-err').html('Phone Length must be a number');status=false;
        }else if(PhoneLength<0){
            $('#txtmPhoneLength-err').html('Phone Length must be greater then equal to 0');status=false;
        }else if(PhoneLength.length>3){
            $('#txtmPhoneLength-err').html('Phone Length may not be greater than 3 digits');status=false;
        }
        return status;
    }
    const validateState=async()=>{
        let status=true;
        $('.New-State-err').html('');
        let CountryName=$('#lstmCountry').val();
        let StateName=$('#txtmStateName').val();
        
        if(CountryName==""){
            $('#lstmCountry-err').html('Country Name is required');status=false;
        }
        if(StateName==""){
            $('#txtmStateName-err').html('State Name is required');status=false;
        }else if(StateName.length<3){
            $('#txtmStateName-err').html('State Name must be atleast 3 characters');status=false;
        }else if(StateName.length>100){
            $('#txtmStateName-err').html('State Name may not be greater than 100 characters');status=false;
        }
        return status;
    }
    const validateCity=async()=>{
        let status=true;
        $('.New-City-err').html('');
        let CountryName=$('#lstmCountry').val();
        let StateName=$('#lstmState').val();
        let CityName=$('#txtmCityName').val();
        
        if(CountryName==""){
            $('#lstmCountry-err').html('Country Name is required');status=false;
        }
        if(StateName==""){
            $('#lstmState-err').html('State Name is required');status=false;
        }
        if(CityName==""){
            $('#txtmCityName-err').html('City Name is required');status=false;
        }else if(CityName.length<3){
            $('#txtmCityName-err').html('City Name must be atleast 3 characters');status=false;
        }else if(CityName.length>100){
            $('#txtmCityName-err').html('City Name may not be greater than 100 characters');status=false;
        }
        return status;
    }
    const getCountries=async(elem)=>{
        let countryID=$('#lstCountry').val();
        let selected=$('#'+elem).attr('data-selected');
        if(selected==undefined){selected="";}
        $('#'+elem).select2('destroy');
        $('#'+elem+' option').remove();
        $('#'+elem).append('<option value="">--Select Country--</option>');
        $.ajax({
            type:"post",
            url:RootUrl+"Get/Countries",
            beforeSend:async()=>{
                $('#btnReloadCountry i').addClass('fa-spin');
            },
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data:{CountryID:countryID},
            async:false,
            dataType:"json",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            complete:async()=>{
                setTimeout(() => {
                    $('#btnReloadCountry i').removeClass('fa-spin');  
                }, 1000);
            },
            success:function(response){
                for(var i=0;i<response.length;i++){
                    if((response[i]['StateID']==selected)||(response[i]['sortname'].split("").join("").toLowerCase()==selected.split("").join("").toLowerCase())){
                        $('#lstCountry').append('<option data-phone-length="'+response[i]['PhoneLength']+'" data-phone-code="'+response[i]['PhoneCode']+'" selected value="'+response[i]['CountryID']+'">'+response[i]['CountryName']+'</option>');
                    }else{
                        $('#lstCountry').append('<option data-phone-length="'+response[i]['PhoneLength']+'" data-phone-code="'+response[i]['PhoneCode']+'" value="'+response[i]['CountryID']+'">'+response[i]['CountryName']+'</option>');
                    }
                }
            }
        })
        $('#'+elem).select2();
    }
    const getStates=async(State)=>{
        let StateID=$('#'+State).val();
        let Country=$('#'+State).attr('data-country-id');
        let countryID=$('#'+Country).val();
        let selected=$('#'+State).attr('data-selected');
        if(selected==undefined){selected="";}
        $('#'+State).select2('destroy');
        $('#'+State+' option').remove();
        $('#'+State).append('<option value="">--Select State--</option>');
        $.ajax({
            type:"post",
            url:RootUrl+"Get/States",
            beforeSend:async()=>{
                $('#btnReloadState i').addClass('fa-spin');
            },
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data:{CountryID:countryID},
            async:false,
            dataType:"json",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            complete:async()=>{
                setTimeout(() => {
                    $('#btnReloadState i').removeClass('fa-spin');  
                }, 1000);
            },
            success:function(response){
                for(var i=0;i<response.length;i++){
                    if((response[i]['StateID']==selected)||(response[i]['StateName'].split(" ").join("").toLowerCase()==selected.split(" ").join("").toLowerCase())){
                        $('#'+State).append('<option selected value="'+response[i]['StateID']+'">'+response[i]['StateName']+'</option>');
                    }else{
                        $('#'+State).append('<option value="'+response[i]['StateID']+'">'+response[i]['StateName']+'</option>');
                    }
                }
                if($('#'+State).val()!=""){
                    $('#'+State).trigger('change');
                }
            }
        })
        $('#'+State).select2();
    }
    const getCities=async(elem)=>{
        let Country=$('#'+elem).attr('data-country-id');
        let countryID=$('#'+Country).val();
        let State=$('#'+elem).attr('data-state-id');
        let StateID=$('#'+State).val();

        let selected=$('#'+elem).attr('data-selected');
        if(selected==undefined){selected="";}
        $('#'+elem).select2('destroy');
        $('#'+elem+' option').remove();
        $('#'+elem).append('<option value="">--Select City--</option>');
        $.ajax({
            type:"post",
            url:RootUrl+"Get/City",
            beforeSend:async()=>{
                $('#btnReloadCity i').addClass('fa-spin');
            },
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data:{CountryID:countryID,StateID:StateID},
            async:false,
            dataType:"json",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            complete:async()=>{
                setTimeout(() => {
                    $('#btnReloadCity i').removeClass('fa-spin');  
                }, 1000);
            },
            success:function(response){
                for(var i=0;i<response.length;i++){
                    if((response[i]['CityID']==selected)||(response[i]['CityName'].split(" ").join("").toLowerCase()==selected.split(" ").join("").toLowerCase())){
                        $('#'+elem).append('<option selected value="'+response[i]['CityID']+'">'+response[i]['CityName']+'</option>');
                    }else{
                        $('#'+elem).append('<option value="'+response[i]['CityID']+'">'+response[i]['CityName']+'</option>');
                    }
                }
            }
        });
        $('#'+elem).select2();
    }
    const countryCreateForm=async(elem)=>{
        $.ajax({
            type:"post",
            url:RootUrl+"Get/Country/Create",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            async:false,
            dataType:"html",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            success:function(response){
                bootbox.dialog({
                    title: 'Create New Country',
                    closeButton: true,
                    message: response,
                    buttons: {
                    }
                });
            }
        })
    }
    const stateCreateForm=async(elem)=>{
        let Country=$('#'+elem).attr('data-country-id');
        let countryID=$('#'+Country).val();
        $.ajax({
            type:"post",
            url:RootUrl+"Get/State/Create",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data:{CountryID:countryID},
            async:false,
            dataType:"html",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            success:function(response){
                bootbox.dialog({
                    title: 'Create New State',
                    closeButton: true,
                    message: response,
                    buttons: {
                    }
                });
            }
        })
    }
    const cityCreateForm=async(elem)=>{
        let Country=$('#'+elem).attr('data-country-id');
        let countryID=$('#'+Country).val();
        let State=$('#'+elem).attr('data-state-id');
        let StateID=$('#'+State).val();
        $.ajax({
            type:"post",
            url:RootUrl+"Get/City/Create",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data:{CountryID:countryID,StateID:StateID},
            async:false,
            dataType:"html",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            success:function(response){
                bootbox.dialog({
                    title: 'Create New City',
                    closeButton: true,
                    message: response,
                    buttons: {
                    }
                });
            }
        })
    }
    $('#btnReloadCountry').click(function(){
        let id=$(this).parent().attr('for');
        getCountries(id);
    });
    $('#btnaddCountry').click(function(){
        let id=$(this).parent().attr('for');
        countryCreateForm(id);
    });
    $('#btnReloadState').click(function(){
        let id=$(this).parent().attr('for');
        getStates(id);
    });
    $('#btnaddState').click(function(){
        let id=$(this).parent().attr('for');
        stateCreateForm(id);
    });
    $('#btnReloadCity').click(function(){
        let id=$(this).parent().attr('for');
        getCities(id);
    });
    $('#btnaddCity').click(function(){
        let id=$(this).parent().attr('for');
        cityCreateForm(id);
    });

    $(document).on('change','#lstmCountry',function(){
        if($('#lstmState').length>0){
            let elem=$('#btnaddCity').parent().attr('for');
            let Country=$('#'+elem).attr('data-country-id');
            $('#'+Country).val($('#lstmCountry').val()).trigger('change');
            getStates('lstmState');
        }
    });
    $(document).on('change','#lstmState',function(){
        let elem=$('#btnaddCity').parent().attr('for');
        let State=$('#'+elem).attr('data-state-id');
        $('#'+State).val($('#lstmState').val()).trigger('change');
    });
    $(document).on('click','#btnCloseModal',function(){
        bootbox.hideAll();
    });
    $(document).on('click','#btnCreateCountry',async function(){
        let status=await validateCountry();
        if(status==true){
            let formData={};
            formData.ShortName=$('#txtmShortName').val();
            formData.CountryName=$('#txtmCountryName').val();
            formData.CallingCode=$('#txtmCallingCode').val();
            formData.PhoneLength=$('#txtmPhoneLength').val();
            swal({
                title: "Are you sure?",
                text: "Do you want add this Country",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, Add it!",
                closeOnConfirm: false
            },
            async function(){
                swal.close();
                btn_Loading($('#btnCreateCountry'));
                $.ajax({
                    type:"post",
                    url:RootUrl+"Store/Countries/Create",
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    data:formData,
                    async:false,
                    dataType:"json",
                    error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                    complete: function(e, x, settings, exception){btn_reset($('#btnCreateCountry'));},
                    success:function(response){ 
                        if(response.status==true){
                            toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            });
                            if( $('#btnCloseModal').length>0){
                                $('#btnCloseModal').trigger('click');
                            }
                            if( $('#btnReloadCountry').length>0){
                                $('#btnReloadCountry').trigger('click');
                            }
                        }else{
                            toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                            if(response['errors']!=undefined){
                                $('.New-Country-err').html('');
                                $.each( response['errors'], function( KeyName, KeyValue ) {
                                    var key=KeyName;
                                    if(key=="ShortName"){$('#txtmShortName-err').html(KeyValue);}
                                    if(key=="CountryName"){$('#txtmCountryName-err').html(KeyValue);}
                                    if(key=="CallingCode"){$('#txtmCallingCode-err').html(KeyValue);}
                                    if(key=="PhoneLength"){$('#txtmPhoneLength-err').html(KeyValue);}
                                });
                            }
                        }
                    }
                })
            });
        }
    });
    $(document).on('click','#btnCreateState',async function(){
        let status=await validateState();
        if(status==true){
            let formData={};
            formData.CountryID=$('#lstmCountry').val();
            formData.StateName=$('#txtmStateName').val();
            swal({
                title: "Are you sure?",
                text: "Do you want add this State",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, Add it!",
                closeOnConfirm: false
            },
            async function(){
                swal.close();
                btn_Loading($('#btnCreateState'));
                $.ajax({
                    type:"post",
                    url:RootUrl+"Store/State/Create",
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    data:formData,
                    async:false,
                    dataType:"json",
                    error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                    complete: function(e, x, settings, exception){btn_reset($('#btnCreateState'));},
                    success:function(response){ 
                        if(response.status==true){
                            toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            });
                            if( $('#btnCloseModal').length>0){
                                $('#btnCloseModal').trigger('click');
                            }
                            if( $('#btnReloadState').length>0){
                                $('#btnReloadState').trigger('click');
                            }
                        }else{
                            toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                            if(response['errors']!=undefined){
                                $('.New-State-err').html('');
                                $.each( response['errors'], function( KeyName, KeyValue ) {
                                    var key=KeyName;
                                    if(key=="StateName"){$('#txtmStateName-err').html(KeyValue);}
                                    if(key=="CountryID"){$('#txtmCountryName-err').html(KeyValue);}
                                });
                            }
                        }
                    }
                })
            });
        }
    });
    $(document).on('click','#btnCreateCity',async function(){
        let status=await validateCity();
        if(status==true){
            let formData={};
            formData.CountryID=$('#lstmCountry').val();
            formData.StateID=$('#lstmState').val();
            formData.CityName=$('#txtmCityName').val();
            swal({
                title: "Are you sure?",
                text: "Do you want add this City",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, Add it!",
                closeOnConfirm: false
            },
            async function(){
                swal.close();
                btn_Loading($('#btnCreateCity'));
                $.ajax({
                    type:"post",
                    url:RootUrl+"Store/City/Create",
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    data:formData,
                    async:false,
                    dataType:"json",
                    error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                    complete: function(e, x, settings, exception){btn_reset($('#btnCreateCity'));},
                    success:function(response){ 
                        if(response.status==true){
                            toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            });
                            if( $('#btnCloseModal').length>0){
                                $('#btnCloseModal').trigger('click');
                            }
                            if( $('#btnReloadCity').length>0){
                                $('#btnReloadCity').trigger('click');
                            }
                        }else{
                            toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                            if(response['errors']!=undefined){
                                $('.New-City-err').html('');
                                $.each( response['errors'], function( KeyName, KeyValue ) {
                                    var key=KeyName;
                                    if(key=="CityName"){$('#txtmCityName-err').html(KeyValue);}
                                    if(key=="StateID"){$('#lstmState-err').html(KeyValue);}
                                    if(key=="CountryID"){$('#txtmCountryName-err').html(KeyValue);}
                                });
                            }
                        }
                    }
                })
            });
        }
    });


    /********************************* Bank ***************************************************************** */
    const validateBank=async()=>{
        let status=true;
        $('.New-Bank-err').html('');
        let TOB=$('#lstmTOB').val();
        let BankName=$('#txtmBankName').val();
        
        if(TOB==""){
            $('#lstmTOB-err').html('Type of Bank is required');status=false;
        }
        if(BankName==""){
            $('#txtmBankName-err').html('Bank Name is required');status=false;
        }else if(BankName.length<3){
            $('#txtmBankName-err').html('Bank Name must be atleast 3 characters');status=false;
        }else if(BankName.length>100){
            $('#txtmBankName-err').html('Bank Name may not be greater than 100 characters');status=false;
        }
        return status;
    }
    const validateBankAccType=async()=>{
        let status=true;
        $('.New-AccType-err').html('');
        let AccountType=$('#txtmAccountType').val();
        if(AccountType==""){
            $('#txtmAccountType-err').html('Account Type is required');status=false;
        }else if(AccountType.length<3){
            $('#txtmAccountType-err').html('Account Type must be atleast 3 characters');status=false;
        }else if(AccountType.length>100){
            $('#txtmAccountType-err').html('Account Type may not be greater than 100 characters');status=false;
        }
        return status;
    }
    const validateBankBranches=async()=>{
        let status=true;
        $('.New-Branch-err').html('');

        let pattern = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
        let BankID=$('#lstmBankName').val();
        let BranchName=$('#txtmBranchName').val();
        let IFSCCode=$('#txtmIFSCCode').val();
        let MICRCode=$('#txtmMICR').val();
        let email=$('#txtmBranchEmail').val();
        if(BankID==""){
            $('#lstmBankName-err').html('The Bank Name is required.');status=false;
        }
        if(BranchName==""){
            $('#txtmBranchName-err').html('The Branch Name is required.');status=false;
        }else if(BranchName.length<3){
            $('#txtmBranchName-err').html('The Branch Name must be atleast 3 characters.');status=false;
        }else if(BranchName.length>100){
            $('#txtmBranchName-err').html('The Branch Name may not be greater than 100 characters.');status=false;
        }
        if(IFSCCode==""){
            $('#txtmIFSCCode-err').html('The IFSC Code is required.');status=false;
        }else if(IFSCCode.length!=11){
            $('#txtmIFSCCode-err').html('The IFSC Code must be 11 digits.');status=false;
        }
        if(MICRCode!=""){
            if(MICRCode.length!=9){
                $('#txtmIFSCCode-err').html('The MICR Code must be 9 digits.');status=false;
            }
        }
        if(email!=""){
            if(pattern.test(email)==false){
                $('#txtmBranchEmail-err').html('The E-Mail must be a valid email address.');status=false;
            }
        }
        return status;
    }
    const getBanks=async(elem)=>{
        let BankID=$('#'+elem).attr('data-selected');
        $('#'+elem).select2('destroy');
        $('#'+elem+' option').remove();
        $('#'+elem+' optgroup').remove();
        $('#'+elem).append('<option value="">--Select a Bank--</option>');
        $.ajax({
            type:"post",
            url:RootUrl+"Get/Bank-List",
            beforeSend:async()=>{
                $('#btnReloadBank i').addClass('fa-spin');
            },
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            dataType:"json",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            complete:async()=>{
                setTimeout(() => {
                    $('#btnReloadBank i').removeClass('fa-spin');  
                }, 1000);
            },
            success:function(response){
                $.each( response, function( KeyName, KeyValue ) {
                    if(KeyValue.length>0){
                        $('#'+elem).append('<optgroup label="'+KeyName+'">');
                        for(var i=0;i<KeyValue.length;i++){
                            if(KeyValue[i]['BankID']==BankID){
                                $('#'+elem).append('<option selected value="'+KeyValue[i]['BankID']+'">'+KeyValue[i]['BankName']+'</option>');
                            }else{
                                $('#'+elem).append('<option value="'+KeyValue[i]['BankID']+'">'+KeyValue[i]['BankName']+'</option>');
                            }
                        }
                        $('#'+elem).append('</optgroup>');
                    }
                });
            }
        })
        $('#'+elem).select2();
    }
    const getBankAccountTypes=async(elem)=>{
        let Selected=$('#'+elem).attr('data-selected');
        if(selected==undefined){selected="";}
        $('#'+elem).select2('destroy');
        $('#'+elem+' option').remove();
        $('#'+elem+' optgroup').remove();
        $('#'+elem).append('<option value="">--Select a Account Type--</option>');
        $.ajax({
            type:"post",
            url:RootUrl+"Get/Bank-Account-Types",
            beforeSend:async()=>{
                $('#btnReloadBAType i').addClass('fa-spin');
            },
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            dataType:"json",
            complete:async()=>{
                setTimeout(() => {
                    $('#btnReloadBAType i').removeClass('fa-spin');  
                }, 1000);
            },
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            success:function(response){
                for(var i=0;i<response.length;i++){
                    if(response[i]['SLNO']==Selected){
                        $('#'+elem).append('<option selected value="'+response[i]['SLNO']+'">'+response[i]['AccountType']+'</option>');
                    }else{
                        $('#'+elem).append('<option value="'+response[i]['SLNO']+'">'+response[i]['AccountType']+'</option>');
                    }
                }
            }
        })
        $('#'+elem).select2();
    }

    const getBankBranches=async(elem)=>{
        let selected=$('#'+elem).attr('data-selected');
        if(selected==undefined){selected="";}
        let Bank=$('#'+elem).attr('data-bank-id');
        let BankID=$('#'+Bank).val();
        $('#'+elem).select2('destroy');
        $('#'+elem+' option').remove();
        $('#'+elem).append('<option value="">--Select a Branch--</option>');
        $.ajax({
            type:"post",
            url:RootUrl+"Get/Bank-Branches",
            beforeSend:async()=>{
                $('#btnReloadBBranch i').addClass('fa-spin');
            },
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data:{BankID:BankID},
            dataType:"json",
            complete:async()=>{
                setTimeout(() => {
                    $('#btnReloadBBranch i').removeClass('fa-spin');  
                }, 1000);
            },
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            success:function(response){
                for(var i=0;i<response.length;i++){
                    if(response[i]['SLNO']==selected){
                        $('#'+elem).append('<option selected data-ifsc="'+response[i]['IFSCCode']+'" value="'+response[i]['SLNO']+'">'+response[i]['BranchName']+'</option>');
                    }else{
                        $('#'+elem).append('<option data-ifsc="'+response[i]['IFSCCode']+'" value="'+response[i]['SLNO']+'">'+response[i]['BranchName']+'</option>');
                    }
                }
            }
        })
        $('#'+elem).select2();
    }
    const BankCreateForm=async(elem)=>{
        $.ajax({
            type:"post",
            url:RootUrl+"Get/Bank-List/Create",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            async:false,
            dataType:"html",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            success:function(response){
                bootbox.dialog({
                    title: 'Add New Bank',
                    closeButton: true,
                    message: response,
                    buttons: {
                    }
                });
            }
        })
    }
    const BankAccTypeCreateForm=async(elem)=>{
        $.ajax({
            type:"post",
            url:RootUrl+"Get/Bank-Account-Types/Create",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            async:false,
            dataType:"html",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            success:function(response){
                bootbox.dialog({
                    title: ' ',
                    closeButton: true,
                    message: response,
                    buttons: {
                    }
                });
            }
        })
    }
    const BankBranchesCreateForm=async(elem)=>{
        let Bank=$('#'+elem).attr('data-bank-id');
        let BankID=$('#'+Bank).val();
        $.ajax({
            type:"post",
            url:RootUrl+"Get/Bank-Branches/Create",
            headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
            data:{BankID:BankID},
            async:false,
            dataType:"html",
            error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
            success:function(response){
                bootbox.dialog({
                    title: 'Branch Details',
                    closeButton: true,
                    message: response,
                    buttons: {
                    }
                });
            }
        })
    }
    $('#btnReloadBank').click(function(){
        let id=$(this).parent().attr('for');
        getBanks(id);
    });
    $('#btnaddBank').click(function(){
        let id=$(this).parent().attr('for');
        BankCreateForm(id);
    });
    $('#btnReloadBAType').click(function(){
        let id=$(this).parent().attr('for');
        getBankAccountTypes(id);
    });
    $('#btnaddBAType').click(function(){
        let id=$(this).parent().attr('for');
        BankAccTypeCreateForm(id);
    });
    $('#btnReloadBBranch').click(function(){
        let id=$(this).parent().attr('for');
        getBankBranches(id);
    });
    $('#btnaddBBranch').click(function(){
        let id=$(this).parent().attr('for');
        BankBranchesCreateForm(id);
    });
    $(document).on('change','#lstmBankName',function(){
        let elem=$('#btnaddBBranch').parent().attr('for');
        let Bank=$('#'+elem).attr('data-bank-id');
        $('#'+Bank).val($('#lstmBankName').val()).trigger('change');
    });
    $(document).on('click','#btnCreateBank',async function(){
        let status=await validateBank();
        if(status==true){
            let formData={};
            formData.TypeOfBank=$('#lstmTOB').val();
            formData.BankName=$('#txtmBankName').val();
            swal({
                title: "Are you sure?",
                text: "Do you want add this Bank",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, Add it!",
                closeOnConfirm: false
            },
            async function(){
                swal.close();
                btn_Loading($('#btnCreateBank'));
                $.ajax({
                    type:"post",
                    url:RootUrl+"Store/Bank-List/Create",
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    data:formData,
                    async:false,
                    dataType:"json",
                    error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                    complete: function(e, x, settings, exception){btn_reset($('#btnCreateBank'));},
                    success:function(response){ 
                        if(response.status==true){
                            toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            });
                            if( $('#btnCloseModal').length>0){
                                $('#btnCloseModal').trigger('click');
                            }
                            if( $('#btnReloadBank').length>0){
                                $('#btnReloadBank').trigger('click');
                            }
                        }else{
                            toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                            if(response['errors']!=undefined){
                                $('.New-Bank-err').html('');
                                $.each( response['errors'], function( KeyName, KeyValue ) {
                                    var key=KeyName;
                                    if(key=="BankName"){$('#txtmBankName-err').html(KeyValue);}
                                    if(key=="TypeOfBank"){$('#lstmTOB-err').html(KeyValue);}
                                });
                            }
                        }
                    }
                })
            });
        }
    });
    $(document).on('click','#btnCreateAccType',async function(){
        let status=await validateBankAccType();
        if(status==true){
            let formData={};
            formData.AccountType=$('#txtmAccountType').val();
            swal({
                title: "Are you sure?",
                text: "Do you want add this Bank Account Type",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, Add it!",
                closeOnConfirm: false
            },async function(){
                swal.close();
                btn_Loading($('#btnCreateAccType'));
                $.ajax({
                    type:"post",
                    url:RootUrl+"Store/Bank-Account-Types/Create",
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    data:formData,
                    async:false,
                    dataType:"json",
                    error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                    complete: function(e, x, settings, exception){btn_reset($('#btnCreateAccType'));},
                    success:function(response){ 
                        if(response.status==true){
                            toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            });
                            if( $('#btnCloseModal').length>0){
                                $('#btnCloseModal').trigger('click');
                            }
                            if( $('#btnReloadBAType').length>0){
                                $('#btnReloadBAType').trigger('click');
                            }
                        }else{
                            toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                            if(response['errors']!=undefined){
                                $('.New-Bank-err').html('');
                                $.each( response['errors'], function( KeyName, KeyValue ) {
                                    var key=KeyName;
                                    if(key=="AccountType"){$('#txtmAccountType-err').html(KeyValue);}
                                });
                            }
                        }
                    }
                })
            });
        }
    });
    $(document).on('click','#btnCreateBranch',async function(){
        let status=await validateBankBranches();
        if(status==true){
            let formData={};
            formData.BankName=$('#lstmBankName').val();
            formData.BranchName=$('#txtmBranchName').val();
            formData.IFSCCode=$('#txtmIFSCCode').val();
            formData.MICR=$('#txtmMICR').val();
            formData.EMail=$('#txtmBranchEmail').val();
            swal({
                title: "Are you sure?",
                text: "Do you want add this Branch",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes, Add it!",
                closeOnConfirm: false
            },async function(){
                swal.close();
                btn_Loading($('#btnCreateBranch'));
                $.ajax({
                    type:"post",
                    url:RootUrl+"Store/Bank-Branches/Create",
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    data:formData,
                    async:false,
                    dataType:"json",
                    error:function(e, x, settings, exception){ajax_errors(e, x, settings, exception);},
                    complete: function(e, x, settings, exception){btn_reset($('#btnCreateBranch'));},
                    success:function(response){ 
                        if(response.status==true){
                            toastr.success(response.message, "Success", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            });
                            if( $('#btnCloseModal').length>0){
                                $('#btnCloseModal').trigger('click');
                            }
                            if( $('#btnReloadBBranch').length>0){
                                $('#btnReloadBBranch').trigger('click');
                            }
                        }else{
                            toastr.error(response.message, "Failed", {
                                positionClass: "toast-top-right",
                                containerId: "toast-top-right",
                                showMethod: "slideDown",
                                hideMethod: "slideUp",
                                progressBar: !0
                            })
                            if(response['errors']!=undefined){
                                $('.New-Branch-err').html('');
                                $.each( response['errors'], function( KeyName, KeyValue ) {
                                    var key=KeyName;
                                    if(key=="BankName"){$('#lstmBankName-err').html(KeyValue);}
                                    if(key=="BranchName"){$('#txtmBranchName-err').html(KeyValue);}
                                    if(key=="IFSCCode"){$('#txtmIFSCCode-err').html(KeyValue);}
                                    if(key=="MICR"){$('#txtmMICR-err').html(KeyValue);}
                                    if(key=="EMail"){$('#txtmBranchEmail-err').html(KeyValue);}
                                });
                            }
                        }
                    }
                })
            });
        }
    });
    /******************************************************************************************************** */
});