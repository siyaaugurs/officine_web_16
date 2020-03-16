<!--Validation start--->
/*----NAME  VALIDATE-start---*/
  function checkCharacter(value , elementName , errMsgElement,disableElement){
	  document.getElementById(errMsgElement).innerHTML = " "; 
	  var nameFix = /^[A-z ]+$/;
	  if(value != ""){
		  if(value.match(nameFix)) {
		     document.getElementById(errMsgElement).innerHTML = " ";
			 document.getElementById(disableElement).disabled = false;
		     return true;
		    }
		  else{
			 document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'>"+ elementName +" field is contain only character .</span>";;
			 document.getElementById(disableElement).disabled = true;
			 return false;
		   } 
		}
	  else{
		  document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'>"+ elementName +" field is Required</span>"; 
		   document.getElementById(disableElement).disabled = true;
		  return false;
		} 	
	}
/*----NAME  VALIDATE-- End--*/

<!--Mobile number checker and Validate start-->	
function mobileNumberValidate(value,elementName,errMsgElement,disableElement){
	//alert(value);
  document.getElementById(errMsgElement).innerHTML = " ";
 var phoneno = /^\d{5,15}$/;
  if(value != ""){
	  if(value.match(phoneno)) {
		 document.getElementById(errMsgElement).innerHTML = " ";
		 document.getElementById(disableElement).disabled = false;
		 return true;
		}
	  else{
		 document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'>Invalid "+ elementName +" Number.</span>";;
	     document.getElementById(disableElement).disabled = true;
		 return false;
	   }
  }
  else{
     document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'>"+ elementName +" Number is required.</span>";
     document.getElementById(disableElement).disabled = true;
	 return false;
  }
}
<!--Mobile number checker and Validate End-->	
<!--Email   number checker and Validate start-->	
function emailValidate(value,elementName,errMsgElement,disableElement){
   document.getElementById(errMsgElement).innerHTML = " ";
   var emailFix = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(value != ""){
		  if(emailFix.test(value)) {
		     document.getElementById(errMsgElement).innerHTML = " ";
		     document.getElementById(disableElement).disabled = false;
		     return true; 
			}
		  else{
			 document.getElementById(errMsgElement).innerHTML = "<psan style='color:red;'>You have entered an invalid "+ elementName +" address.</span>";
			 document.getElementById(disableElement).disabled = true;
			 return false;
		   } 
		}
	  else{
		  document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'>"+ elementName +" is required </span>"; 
		  document.getElementById(disableElement).disabled = true;
		  return false;
		} 	 
 }
<!--Email   number checker and Validate End-->

<!--Email   number checker and Validate End-->

function signUpemailValidate(value,elementName,errMsgElement){
   document.getElementById(errMsgElement).innerHTML = " ";
   var emailFix = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(value != ""){
		  if(emailFix.test(value)) {
		     document.getElementById(errMsgElement).innerHTML = " ";
		     return true; 
			}
		  else{
			 document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'>You have entered an invalid "+ elementName +" address.</span>";
			 return false;
		   } 
		}	 
 }
<!--Email   number checker and Validate End-->


<!--validation End--->

<!---pincode validation from all website in --start--->
 function pincodeValidateBackend(pincodevalue,elementName,errMsgElement,disableElement){
   document.getElementById(errMsgElement).innerHTML = " ";
    var pinCodeNo = /^\d{6}$/;
	 if(pincodevalue != ""){
		if(pincodevalue.match(pinCodeNo)) {
		   $.ajax({
	         url:base_url+'/checkpinCodeValidation',
		     type:"GET",
		     data:{pincodevalue:pincodevalue},
		     success: function(data){
			    if(data==1){
				    document.getElementById(errMsgElement).innerHTML = " ";
					document.getElementById(disableElement).disabled = false;
				  }
				else{
				    document.getElementById(errMsgElement).innerHTML = data;
					document.getElementById(disableElement).disabled = true;
				  }   
		     }
	        });
		   document.getElementById(errMsgElement).innerHTML = " ";
		   document.getElementById(disableElement).disabled = false;
		 }
		else{
		  document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'><strong>Invalid Pin Code Number.</strong></span>";
			 document.getElementById(disableElement).disabled = true;
		   }
	  }
	  else{
		 document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'><strong>Pin Code Number is Required.</strong></span>";
		 document.getElementById(disableElement).disabled = true;
	  }
 }
<!---pincode validation from all website in --End--->

<!--validate maximum caharacter lenght validation start---->
function maxCharacterLenght(value,elementName,errMsgElement,maxtextLenght){
    var textLenght = value.length;
	var saveCharcterlenght = maxtextLenght - textLenght;
	 if(saveCharcterlenght < 1){
		document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'><strong>" + elementName +" maximum 100 Character  .</strong></span>";
	  }
	 else{
        document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'><strong> "+ saveCharcterlenght +" Character Remaining .</strong></span>";
	 }
 }
<!--validate maximum caharacter lenght validation End---->

<!--check alpha numeric value validation start-->
function checkAlphaNumeric(value,elementName,errMsgElement,hideElement,textlenght){
     regexp = /^[A-Za-z0-9]+$/;
     if(value != ""){
	   if(regexp.test(value)){
		  if(value.length < textlenght){
			   document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'><strong> " + elementName  +" at teast "+ textlenght + " .</strong></span>";
	           document.getElementById(hideElement).disabled = true;
			}
		   else{
			  document.getElementById(errMsgElement).innerHTML = " ";
			   document.getElementById(hideElement).disabled = false;
			}	 
         }
       else{
         document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'><strong> " + elementName  +" only contain alpha numeric value  .</strong></span>";
	     document.getElementById(hideElement).disabled = true;
         }
	  }
   else{
	   document.getElementById(errMsgElement).innerHTML = "<span style='color:red;'><strong> " + elementName  +" field is required . .</strong></span>";
	   document.getElementById(hideElement).disabled = true;
	  }	 
}
<!--check alpha numeric value validation End-->

function imageExtValidation(fileValue,fieldName,extArr,hideElement,msgErrelement){
   var file = fileValue.toLowerCase();
   var extension = file.substring(file.lastIndexOf('.') + 1); //alert(extArr);
    if($.inArray(extension, extArr) > -1) {
          document.getElementById(msgErrelement).innerHTML = " ";  
		  document.getElementById(hideElement).disabled = false;
       }
    else{
       document.getElementById(msgErrelement).innerHTML = "<span style='color:red;'><strong> " + fieldName  +" only support png , jpeg , jpg extension format .</strong></span>";  
		  document.getElementById(hideElement).disabled = true;
     }
}



<!--Date time Calender add script start-->
<!--Date time Calender add script start-->

<!--About business Character limitation start-->
 function checkCharacterLimit(textValue,fieldName,msgErrelement,maxlenght){
	 var textLenght = textValue.length;
	  var saveCharcterlenght = maxlenght - textLenght;
		if(saveCharcterlenght < 1){
		  document.getElementById(msgErrelement).innerHTML = "<span style='color:red;'>" + fieldName  +" maximum "+ maxlenght +" Character .</span>";  
		}
		else{
		   document.getElementById(msgErrelement).innerHTML = "<span style='color:red;'> " + saveCharcterlenght  +" Character Remaining .</span>";
		}
 
 }
<!--About business Character limitation start-->	 


	 
<!--User Advertisement Registration Age Validation start -->
$(document).on('change','#ageTo',function(){
	$("#ageErr").html(" ");
	$("#postsubmit").attr('disabled',true);
   var ageTo = parseInt($('#ageTo').val());
   var ageFrom = parseInt($("#ageFrom").val());
   if(ageTo <= ageFrom){
	   $("#ageErr").html("<span style='color:red;'><strong>Please Select Valid age</strong></span>");
	   $("#postsubmit").attr('disabled',true);
	 } 
   else{
	   $("#ageErr").html(" ");
	   $("#postsubmit").attr('disabled',false); 
	 }	 
});
<!-- User Advertisement Registration Age Validation End -->	 



/*----passowrd match validation--------*/
function passowrdMatch(){
   var password = document.getElementById('password').value;
   if(password==""){
	    document.getElementById('password_err_msg').innerHTML = "<span style='color:red;'> Password is required.</span>"; 
		document.getElementById("registration_submit").disabled = true;
		return false;
	 }
   else{
	    var confirmPassword = document.getElementById('confirm_password').value;
        if(confirmPassword != password){
		    document.getElementById('confirmPwdError').innerHTML = "<span style='color:red;'>Password does not Match...</span>"; 
			document.getElementById("registration_submit").disabled = true;
			return false;
		  }	
		else{
		  document.getElementById('confirmPwdError').innerHTML = "<span style='color:green;'>Password match.</span>"; 
		  document.getElementById("registration_submit").disabled = false;
		  return true;
		 }  
	 }	 
  
}
/*----passowrd match validation--------*/	

	
/*--Check password Strenght script start--*/
function CheckPasswordStrength() {
            var password_strength = document.getElementById("password_err_msg");
			var password = document.getElementById('password').value;
            //TextBox left blank.
            if (password.length == 0) {
                password_strength.innerHTML = "";
                return;
            }

            //Regular Expressions.
            var regex = new Array();
            regex.push("[A-Z]"); //Uppercase Alphabet.
            regex.push("[a-z]"); //Lowercase Alphabet.
            regex.push("[0-9]"); //Digit.
            regex.push("[$@$!%*#?&]"); //Special Character.

            var passed = 0;

            //Validate for each Regular Expression.
            for (var i = 0; i < regex.length; i++) {
                if (new RegExp(regex[i]).test(password)) {
                    passed++;
                }
            }
            //Validate for length of Password.
            if (passed > 2 && password.length > 8) {
                passed++;
            }

            //Display status.
            var color = "";
            var strength = "";
            switch (passed) {
                case 0:
                case 1:
                    strength = "<strong> Weak </strong>";
                    color = "red";
                    break;
                case 2:
                    strength = "<strong> Good </strong>";
                    color = "darkorange";
                    break;
                case 3:
                case 4:
                    strength = "<strong> Strong </strong>";
                    color = "green";
                    break;
                case 5:
                    strength = "<strong> Very Strong </strong>";
                    color = "darkgreen";
                    break;
            }
            password_strength.innerHTML = strength;
            password_strength.style.color = color;
        }
/*---Check password strenght script End----*/



			