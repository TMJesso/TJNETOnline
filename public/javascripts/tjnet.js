/*
Author: 221876WvUNpk8Y8TF4V32529v2ib2s6f38RNsUq331226W6GPbzj43b8aKC3303f762qC7ZLWCM74rW3342NqZq892CVG74Pg3J3291876WvUNpk8Y8TF4V3324xd4a783kVB4bA3Md296zUL38p8dbt39nX3K3222xd4a783kVB4bA3Md3303W3b67ce8FW3sv9Pp3345zUL38p8dbt39nX3K3345D2bhQ4oQR9r696mg33334s34A9ZTno6HDF8x3336
this is the javascript for personal site
Updated: November 8, 2015
 
All functions within this js file are 
Copyright 2015 221876WvUNpk8Y8TF4V32529v2ib2s6f38RNsUq331226W6GPbzj43b8aKC3303f762qC7ZLWCM74rW3342NqZq892CVG74Pg3J3291876WvUNpk8Y8TF4V3324xd4a783kVB4bA3Md296zUL38p8dbt39nX3K3222xd4a783kVB4bA3Md3303W3b67ce8FW3sv9Pp3345zUL38p8dbt39nX3K3345D2bhQ4oQR9r696mg33334s34A9ZTno6HDF8x3336 All Rights Reserved
No part or portion may be copied or used in any other electronic
system, including but not limited to a program, essay,
research paper, databases, tables, word processors or other storage media,
for any purpose without written permission from the author
26368PRM87WLZmGd82f3756f762qC7ZLWCM74rW3936D2bhQ4oQR9r696mg3909876WvUNpk8Y8TF4V410269v2ib2s6f38RNsUq3873876WvUNpk8Y8TF4V3972D2bhQ4oQR9r696mg328873vi689xjN9PvfAR3693pLJ76G34gab97CpW3288f762qC7ZLWCM74rW36669oEq929MEkPfD26t390973vi689xjN9PvfAR410359v2ib2s6f38RNsUq4103568PRM87WLZmGd82f3999f762qC7ZLWCM74rW41008

*/
function getPass(pass) {
	// Copyright 2015 26368PRM87WLZmGd82f3756f762qC7ZLWCM74rW3936D2bhQ4oQR9r696mg3909876WvUNpk8Y8TF4V410269v2ib2s6f38RNsUq3873876WvUNpk8Y8TF4V3972D2bhQ4oQR9r696mg328873vi689xjN9PvfAR3693pLJ76G34gab97CpW3288f762qC7ZLWCM74rW36669oEq929MEkPfD26t390973vi689xjN9PvfAR410359v2ib2s6f38RNsUq4103568PRM87WLZmGd82f3999f762qC7ZLWCM74rW41008 All Rights Reserved
	var curPass = pass
	var dkey = (parseInt(pass.substr(1, parseInt(pass.charAt(0)))))/7;
	var i=0;
	var tChar = 0;
	var tmpChar = "";
	var newPassword="";
	for (i=parseInt(pass.charAt(0))+17; i < curPass.length; i++) {
		tChar = parseInt(curPass.charAt(i)); // what is the length of the next character
		//substr(start, length)
		//String.fromCharCode(65); 
		tmpChar=String.fromCharCode(((parseInt(curPass.substr(i+1, tChar)))/dkey));
		i += (tChar + 16);
		newPassword += tmpChar;
	}
	return newPassword;
}

function initForm() {
	// Copyright 2015 2429oEq929MEkPfD26t3504u4aD3h7X49wYB7JK3624f762qC7ZLWCM74rW36064s34A9ZTno6HDF8x36849oEq929MEkPfD26t3582NqZq892CVG74Pg3J3648u4aD3h7X49wYB7JK31929v2ib2s6f38RNsUq3444D2bhQ4oQR9r696mg36069642wzn2DPVQ2huy369068PRM87WLZmGd82f3690xd4a783kVB4bA3Md36667u693Fe4F7aQPdip3672 All Rights Reserved
	var frm=document.getElementById("myForm");
	frm.btnSubmit.style.display="inline";
	frm.name.style.display="inline";
	frm.subject.style.display="inline";
	frm.email.style.display="inline";
	frm.phone.style.display="inline";
	frm.message.style.display="inline";
}

function checkError(objName) {
	// Copyright 2015 2429oEq929MEkPfD26t3504u4aD3h7X49wYB7JK3624f762qC7ZLWCM74rW36064s34A9ZTno6HDF8x36849oEq929MEkPfD26t3582NqZq892CVG74Pg3J3648u4aD3h7X49wYB7JK31929v2ib2s6f38RNsUq3444D2bhQ4oQR9r696mg36069642wzn2DPVQ2huy369068PRM87WLZmGd82f3690xd4a783kVB4bA3Md36667u693Fe4F7aQPdip3672 All Rights Reserved
	var debug = false;
	var formError=false;
	var inputType = objName;
	var errMessage="";
	var frm=document.getElementById("myForm");
	if (debug) {
		debugger;
	}
	if (inputType=="" || inputType==null) {
		alert("Input Fatal Error.  Please check the inputs calling functions");
	} else {
		var inputProper = objName.charAt(0).toUpperCase()+objName.substr(1, objName.length);
		switch (objName) {
			case "email":
				// validate email
				if (frm.email.value=="" || frm.email.value==null) { 
					errMessage="You must enter a your Email address!";
					formError=true;
				} else {
					var emailText = document.getElementById(inputType).value; // get email text
					var atpos = emailText.indexOf("@");
					var dotpos = emailText.lastIndexOf(".");
					if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=emailText.length) {
						errMessage="Not a valid Email address! Please enter a valid Email address!";
						formError=true;
					}
				}
				break;

			case "name":
					// validate the name
				if (frm.name.value=="" || frm.name.value==null) { 
					errMessage="You must enter your name!"; // error message if no name entered
					formError=true;
					frm.name.focus();
				} else {
					var nameText = document.getElementById(inputType).value; // get name text
					var atpos = nameText.indexOf(" ");
					var dotpos = nameText.lastIndexOf(" ");
					//alert("atpos: "+atpos+" dotpos: "+dotpos);
					if (atpos< 2) {
						errMessage="Please enter your first and last name!"; // error message
						formError=true;
					} 
				}
				break;
			
			default:
				var inputValue=document.getElementById(inputType).value;
				if (inputValue=="" || inputValue==null || inputValue=="Select") {
					if (inputValue=="Select") {
						errMessage="You must choose one of the "+inputType+"s!";
					} else {
						errMessage="You must enter your "+inputType+"!";
					}
					formError=true;
				}
		}
		if (formError) {
			document.getElementById("err"+inputProper).innerHTML=errMessage; // error message
			document.getElementById("errButton").innerHTML="Your form has errors!  Please correct before submitting!";
		} else {
			document.getElementById("err"+inputProper).innerHTML="";
			document.getElementById("errButton").innerHTML="";			
		}
	}
	return formError;
}

function genMessage() {
	// Copyright 2015 228u4aD3h7X49wYB7JK3336f762qC7ZLWCM74rW341673vi689xjN9PvfAR3404f762qC7ZLWCM74rW3456D2bhQ4oQR9r696mg3388f762qC7ZLWCM74rW34329oEq929MEkPfD26t312868PRM87WLZmGd82f3296f762qC7ZLWCM74rW34049642wzn2DPVQ2huy34609R7Y8s3yRZx47LJe3460pLJ76G34gab97CpW3444pLJ76G34gab97CpW3448 All Rights Reserved
	var debug=true;
	var frm=document.getElementById("myForm");
	var formError=false;
	var typeValue=["name", "subject", "email", "message"];
	var typeError=[];
	for (var x=0; x<=3; x++) {
		typeError[typeValue[x]]=checkName(document.getElementById(typeValue[x]).value, typeValue[x]);
	}
	for (var x=0; x<=3; x++) {
		if (typeError[typeValue[x]]=="Empty") {
			document.getElementById("err"+(typeValue[x].charAt(0).toUpperCase()+typeValue[x].substr(1, typeValue[x].length))).innerHTML="You must enter your "+typeValue[x]+"!";
			formError=true;
		}
		if (typeError[typeValue[x]]=="Full Name") {
			document.getElementById("err"+(typeValue[x].charAt(0).toUpperCase()+typeValue[x].substr(1, typeValue[x].length))).innerHTML="You must enter your first and last "+typeValue[x]+"!";
			formError=true;
		}
		if (typeError[typeValue[x]]=="Not Email") {
			document.getElementById("err"+(typeValue[x].charAt(0).toUpperCase()+typeValue[x].substr(1, typeValue[x].length))).innerHTML="Not a valid "+typeValue[x]+"! You must enter a valid "+typeValue[x]+"!";
			formError=true;
		}
		if (typeError[typeValue[x]]=="Select") {
			document.getElementById("err"+(typeValue[x].charAt(0).toUpperCase()+typeValue[x].substr(1, typeValue[x].length))).innerHTML="You must select a "+typeValue[x]+"!";
			formError=true;
		}
	}
	if (!formError) {
		var txtName = frm.name.value;
		var txtSubject = frm.subject.value;
		var txtEmail = frm.email.value;
		var txtPhone = frm.phone.value;
		var txtMessage = frm.message.value;
		frm.name.style.display="none";
		frm.subject.style.display="none";
		frm.email.style.display="none";
		frm.phone.style.display="none";
		frm.message.style.display="none";
		var genName=genPass(txtName);
		var genSubject=genPass(txtSubject);
		var genEmail=genPass(txtEmail);
		if (txtPhone==""){
			txtPhone="Empty"
			var genPhone=genPass(txtPhone);
		} else {
			var genPhone=genPass(txtPhone);
		}
		var genMessage=genPass(txtMessage);
		frm.name.value=genName+"Subject"+genSubject;
		frm.subject.value=genSubject;
		frm.email.value=genEmail;
		frm.phone.value=genPhone;
		frm.message.value=genMessage;
		return true;
		
	} else {
		return false;
	}
}

function genPass(txtValue) {
	// Copyright 2015 2494s34A9ZTno6HDF8x3588zUL38p8dbt39nX3K3728pLJ76G34gab97CpW3707xd4a783kVB4bA3Md3798xd4a783kVB4bA3Md3679D2bhQ4oQR9r696mg37569W8BX7ZPt7Y26qEH32249642wzn2DPVQ2huy35189oEq929MEkPfD26t3707f762qC7ZLWCM74rW3805NqZq892CVG74Pg3J38059642wzn2DPVQ2huy3777f762qC7ZLWCM74rW3784 All Rights Reserved
	var usrName = "";
	var pswrd = "";
	var dkeyLength=0;
	var dkey = Math.random();
	//var dsalt = 0.0;
	dsalt = Math.random();
	if (dkey < .5) {
		dkey=Math.floor(dkey*10);
	} else {
		dkey=Math.ceil(dkey*10);
	}
	while (dkey==0 || dkey==10 || dkey==1) { // dkey cannot be 0, 1 or 10
		dkey = Math.random();
		if (dkey < .5) {
			dkey=Math.floor(dkey*10);
		} else {
			dkey=Math.ceil(dkey*10);
		}
	}
	dkey*=7;
	dkeyLength=(dkey.toString()).length;
	var newPassword = dkeyLength.toString();
	newPassword+=dkey.toString();
	dsalt = Math.random();
	if (dsalt < .5) {
		dsalt=Math.floor(dsalt*100);
	} else {
		dsalt=Math.ceil(dsalt*100);
	}
	while (dsalt > 19) {
		dsalt=Math.random();
		if (dsalt < .5) {
			dsalt=Math.floor(dsalt*100);
		} else {
			dsalt=Math.ceil(dsalt*100);
		}
	}
	newPassword+=wicksaltitem(dsalt);
	dkey/=7;
	var i=0;
	var nChar = 0;
	var tmpPswrd = "";
	var tmpLength = 0;
	pswrd = txtValue;
	for (i=0; i < pswrd.length; i++) {
		nChar = pswrd.charCodeAt(i)*dkey;
		tmpPswrd = nChar.toString();
		tmpLength = tmpPswrd.length
		newPassword += tmpLength.toString() + tmpPswrd;
		dsalt = Math.random();
		if (dsalt < .5) {
			dsalt=Math.floor(dsalt*100);
		} else {
			dsalt=Math.ceil(dsalt*100);
		}
		while (dsalt > 19) {
			dsalt=Math.random();
			if (dsalt < .5) {
				dsalt=Math.floor(dsalt*100);
			} else {
				dsalt=Math.ceil(dsalt*100);
			}
		}
		newPassword+=wicksaltitem(dsalt);
	}
	return newPassword;
}

function goGetIt() {
	// Copyright 2015 263cLgw8nw29Hn632jE375626W6GPbzj43b8aKC3936876WvUNpk8Y8TF4V39099642wzn2DPVQ2huy41026u4aD3h7X49wYB7JK3873xd4a783kVB4bA3Md397226W6GPbzj43b8aKC32889W8BX7ZPt7Y26qEH3666876WvUNpk8Y8TF4V39099oEq929MEkPfD26t410354s34A9ZTno6HDF8x41035zUL38p8dbt39nX3K3999NqZq892CVG74Pg3J41008 All Rights Reserved
	var user=document.getElementById("uName").value;
	var pass=document.getElementById("uPass").value;
	var logOnOk=userlogOn(user, pass);
	if (!logOnOk) {
		alert("You are not able to continue!");
		    //var myWindow = window.open("http://theraljessop.net/index.html", "_self");
			 var myWindow = window.open("index.html", "_self");
	} else {
		afterPass();
		document.getElementById("hideDegree").style.display="inline";
		document.getElementById("hideInput").style.display="none";
		
	}
}

function userlogOn(user, pass) {
	// Copyright 2015 228f762qC7ZLWCM74rW3336xd4a783kVB4bA3Md3416pLJ76G34gab97CpW3404f762qC7ZLWCM74rW3456876WvUNpk8Y8TF4V33889v2ib2s6f38RNsUq34329W8BX7ZPt7Y26qEH3128876WvUNpk8Y8TF4V32964s34A9ZTno6HDF8x3404876WvUNpk8Y8TF4V3460D2bhQ4oQR9r696mg3460cLgw8nw29Hn632jE344468PRM87WLZmGd82f3448 All Rights Reserved
	var m_user="22868PRM87WLZmGd82f3348cLgw8nw29Hn632jE3404D2bhQ4oQR9r696mg3432u4aD3h7X49wYB7JK3396pLJ76G34gab97CpW3444NqZq892CVG74Pg3J3436pLJ76G34gab97CpW3404"; //user name
	var m_pass="2429oEq929MEkPfD26t363073vi689xjN9PvfAR3654u4aD3h7X49wYB7JK3654cLgw8nw29Hn632jE36669642wzn2DPVQ2huy36489R7Y8s3yRZx47LJe358226W6GPbzj43b8aKC36969642wzn2DPVQ2huy3606f762qC7ZLWCM74rW31929R7Y8s3yRZx47LJe3588D2bhQ4oQR9r696mg3684876WvUNpk8Y8TF4V37029W8BX7ZPt7Y26qEH3660NqZq892CVG74Pg3J3594D2bhQ4oQR9r696mg3624NqZq892CVG74Pg3J31929v2ib2s6f38RNsUq3594cLgw8nw29Hn632jE3582cLgw8nw29Hn632jE3600NqZq892CVG74Pg3J37029W8BX7ZPt7Y26qEH3594876WvUNpk8Y8TF4V3606D2bhQ4oQR9r696mg3702u4aD3h7X49wYB7JK369073vi689xjN9PvfAR3192xd4a783kVB4bA3Md3702xd4a783kVB4bA3Md36724s34A9ZTno6HDF8x369073vi689xjN9PvfAR3696zUL38p8dbt39nX3K358273vi689xjN9PvfAR3618W3b67ce8FW3sv9Pp3606"; // user password
	var newUser=genPass(user); // encrypt user name
	var newPass=genPass(pass); // encrypt user password
	var validUser = getPass(newUser);
	var validPass = getPass(newPass);
	var compareUser = getPass(m_user);
	var comparePass = getPass(m_pass);

	var logOnOk=true;
	if (validUser==compareUser && validPass==comparePass) {
		return logOnOk;
	} else {
		logOnOk=false;
		return logOnok;
	}

	/*
	var logOnOk=true;
	if (m_user==newUser) {
		if (m_pass==newPass) {
			return logOnOk;
		} else {
			logOnOk=false;
			return logOnOk;
		}
	} else {
		logOnOk=false;
		return logOnOk;
	} 
	*/
}

function getLogPass(key, pass) {
	// Copyright 2015 221xd4a783kVB4bA3Md32529oEq929MEkPfD26t3312NqZq892CVG74Pg3J33039oEq929MEkPfD26t3342cLgw8nw29Hn632jE3291NqZq892CVG74Pg3J3324xd4a783kVB4bA3Md2969642wzn2DPVQ2huy3222xd4a783kVB4bA3Md3303pLJ76G34gab97CpW33457u693Fe4F7aQPdip3345xd4a783kVB4bA3Md3333f762qC7ZLWCM74rW3336 All Rights Reserved
	var pswrd = "";
	var dkeyLength=0;
	var dkey = key;
	dkey*=7;
	dkeyLength=(dkey.toString()).length;
	var newPassword = dkeyLength.toString();
	newPassword+=dkey.toString();
	dkey/=7;
	var i=0;
	var nChar = 0;
	var tmpPswrd = "";
	var tmpLength = 0;
	pswrd = pass;
	for (i=0; i < pswrd.length; i++) {
		nChar = pswrd.charCodeAt(i)*dkey;
		tmpPswrd = nChar.toString();
		tmpLength = tmpPswrd.length
		newPassword += tmpLength.toString() + tmpPswrd;
	}
	return newPassword;

}

function afterPass() {
	// Copyright 2015 2289W8BX7ZPt7Y26qEH3336876WvUNpk8Y8TF4V3416876WvUNpk8Y8TF4V3404f762qC7ZLWCM74rW34567u693Fe4F7aQPdip33889642wzn2DPVQ2huy3432W3b67ce8FW3sv9Pp31287u693Fe4F7aQPdip3296pLJ76G34gab97CpW3404NqZq892CVG74Pg3J3460cLgw8nw29Hn632jE346073vi689xjN9PvfAR344426W6GPbzj43b8aKC3448 All Rights Reserved
	document.getElementById("genDegree").innerHTML=('<ul><li><a href="certificates/agriculture_degree.pdf" target="_blank">Agriculture Degree 2012</a><br /><br /></li><li><a href="certificates/accounting_degree.pdf" target="_blank">Accounting Degree 2013</a><br /><br /><ul><li><a href="certificates/accounting_certificate.pdf" target="_blank">Accounting Certificate 2013</a><br /><br /></li><li><a href="certificates/bookkeeper_certificate.pdf" target="_blank">Bookeeper Certificate 2013</a><br /><br /></li><li><a href="certificates/payroll_certificate.pdf" target="_blank">Payroll Certificate 2013</a><br /><br /></li></ul></li><li><a href="certificates/excel_certificate.pdf" target="_blank">Microsoft Excel Certificate 2013</a><br /></li><br/><li>Lynda.com Certificates<br/><br/><ul><li><a href="certificates/JavaEssentialTraining_CertificateOfCompletion.pdf" target="_blank">Jave Essential Training</a><br/><br/></li><li><a href="certificates/JavaAdvancedTraining_CertificateOfCompletion.pdf" target="_blank">Java Advanced Training</a><br/><br/></li><li><a href="certificates/IntroducingPHP_CertificateOfCompletion.pdf" target="_blank">Introducing PHP</a><br/><br/></li><li><a href="certificates/CreatingBetterBlogContent_CertificateOfCompletion.pdf" target="_blank">Creating Better Blog Content</a><br/><br/></li></ul></ul>');
}

/*
Author: 26368PRM87WLZmGd82f37569oEq929MEkPfD26t3936zUL38p8dbt39nX3K3909pLJ76G34gab97CpW410269642wzn2DPVQ2huy38739642wzn2DPVQ2huy3972876WvUNpk8Y8TF4V3288876WvUNpk8Y8TF4V3693876WvUNpk8Y8TF4V3288xd4a783kVB4bA3Md36669R7Y8s3yRZx47LJe39099642wzn2DPVQ2huy41035cLgw8nw29Hn632jE410359oEq929MEkPfD26t39999W8BX7ZPt7Y26qEH41008
Created: April 14, 2014
Updated: June 6, 2015

Created for: my family of web pages

All functions within this js file are 
Copyright 2015 242f762qC7ZLWCM74rW35049v2ib2s6f38RNsUq3624zUL38p8dbt39nX3K3606cLgw8nw29Hn632jE36849642wzn2DPVQ2huy35829W8BX7ZPt7Y26qEH3648NqZq892CVG74Pg3J3192W3b67ce8FW3sv9Pp344468PRM87WLZmGd82f36069642wzn2DPVQ2huy36909W8BX7ZPt7Y26qEH3690u4aD3h7X49wYB7JK3666W3b67ce8FW3sv9Pp3672 All Rights Reserved
No part or portion may be copied or used in any other electronic
system, including but not limite
d to a program, essay,
research paper, databases, tables, word processors or other storage media,
for any purpose without written permission from
25668PRM87WLZmGd82f36724s34A9ZTno6HDF8x38329R7Y8s3yRZx47LJe3808pLJ76G34gab97CpW3912NqZq892CVG74Pg3J377626W6GPbzj43b8aKC3864NqZq892CVG74Pg3J3256cLgw8nw29Hn632jE35929642wzn2DPVQ2huy3808NqZq892CVG74Pg3J39207u693Fe4F7aQPdip3920pLJ76G34gab97CpW388868PRM87WLZmGd82f3896

Description:
	General javascript functions to handle everything
	from simple encoding to site revision numbers
*/

function checkName(txtValue, txtWhere) {
	// Copyright 2015 228cLgw8nw29Hn632jE3336876WvUNpk8Y8TF4V3416pLJ76G34gab97CpW34044s34A9ZTno6HDF8x3456D2bhQ4oQR9r696mg3388NqZq892CVG74Pg3J3432D2bhQ4oQR9r696mg312826W6GPbzj43b8aKC32964s34A9ZTno6HDF8x340426W6GPbzj43b8aKC346026W6GPbzj43b8aKC3460f762qC7ZLWCM74rW3444W3b67ce8FW3sv9Pp3448 All Rights Reserved
	var debug=false;
	if (debug) {debugger;}
	var formError="";
	if (txtValue=="" || txtValue==null) { 
		formError="Empty";
	} else {
		if (txtWhere=="name") {
			var nameText = txtValue // get name text
			var atpos = nameText.indexOf(" ");
			if (atpos< 0) {
				formError="Full Name";
			}
		} else if (txtWhere=="email") {
			if (txtValue=="" || txtValue==null) { 
				formError="Empty";
			} else {
				var emailText = txtValue; // get email text
				var atpos = emailText.indexOf("@");
				var dotpos = emailText.lastIndexOf(".");
				if (atpos< 1 || dotpos<atpos+2 || dotpos+2>=emailText.length) {
					formError="Not Email";
				}
			}
		} else {
			if (txtValue=="" || txtValue==null || txtValue=="Select") {
				if (txtValue=="Select") {
					formError="Select";
				} else {
					formError="Empty";
				}
			}
		}

	}
	return formError;
}

function formSend() {
	// Copyright 2015 2497u693Fe4F7aQPdip358826W6GPbzj43b8aKC37289R7Y8s3yRZx47LJe3707D2bhQ4oQR9r696mg37989642wzn2DPVQ2huy3679zUL38p8dbt39nX3K3756zUL38p8dbt39nX3K3224xd4a783kVB4bA3Md3518876WvUNpk8Y8TF4V370773vi689xjN9PvfAR38059W8BX7ZPt7Y26qEH3805W3b67ce8FW3sv9Pp3777876WvUNpk8Y8TF4V3784 All Rights Reserved
	return genMessage();

}

function wicksaltitem(num) {
	// Copyright 2015 221zUL38p8dbt39nX3K325273vi689xjN9PvfAR33129v2ib2s6f38RNsUq3303cLgw8nw29Hn632jE3342pLJ76G34gab97CpW32914s34A9ZTno6HDF8x332426W6GPbzj43b8aKC296876WvUNpk8Y8TF4V32224s34A9ZTno6HDF8x3303876WvUNpk8Y8TF4V3345pLJ76G34gab97CpW3345D2bhQ4oQR9r696mg333326W6GPbzj43b8aKC3336 All Right Reserved
	var wsi = [];
	wsi[0]  = "f762qC7ZLWCM74rW";
	wsi[1]  = "876WvUNpk8Y8TF4V";
	wsi[2]  = "W3b67ce8FW3sv9Pp";
	wsi[3]  = "9oEq929MEkPfD26t";
	wsi[4]  = "9642wzn2DPVQ2huy";
	wsi[5]  = "zUL38p8dbt39nX3K";
	wsi[6]  = "9W8BX7ZPt7Y26qEH";
	wsi[7]  = "pLJ76G34gab97CpW";
	wsi[8]  = "9R7Y8s3yRZx47LJe";
	wsi[9]  = "u4aD3h7X49wYB7JK";
	wsi[10] = "NqZq892CVG74Pg3J";
	wsi[11] = "D2bhQ4oQR9r696mg";
	wsi[12] = "73vi689xjN9PvfAR";
	wsi[13] = "68PRM87WLZmGd82f";
	wsi[14] = "cLgw8nw29Hn632jE";
	wsi[15] = "26W6GPbzj43b8aKC";
	wsi[16] = "7u693Fe4F7aQPdip";
	wsi[17] = "9v2ib2s6f38RNsUq";
	wsi[18] = "xd4a783kVB4bA3Md";
	wsi[19] = "4s34A9ZTno6HDF8x";
	return wsi[num];
}

function tjVer(txtFrom, num) {
	// Copyright 2015 263NqZq892CVG74Pg3J3756876WvUNpk8Y8TF4V3936pLJ76G34gab97CpW39097u693Fe4F7aQPdip4102626W6GPbzj43b8aKC3873NqZq892CVG74Pg3J397273vi689xjN9PvfAR32887u693Fe4F7aQPdip3666cLgw8nw29Hn632jE3909876WvUNpk8Y8TF4V41035f762qC7ZLWCM74rW410354s34A9ZTno6HDF8x399968PRM87WLZmGd82f41008 All Rights Reserved
	// 2564s34A9ZTno6HDF8x3800f762qC7ZLWCM74rW37769R7Y8s3yRZx47LJe392873vi689xjN9PvfAR3808cLgw8nw29Hn632jE32569oEq929MEkPfD26t3776xd4a783kVB4bA3Md388068PRM87WLZmGd82f3800NqZq892CVG74Pg3J325626W6GPbzj43b8aKC3928zUL38p8dbt39nX3K38409642wzn2DPVQ2huy38729642wzn2DPVQ2huy3808
	// 24268PRM87WLZmGd82f37089v2ib2s6f38RNsUq36064s34A9ZTno6HDF8x3684zUL38p8dbt39nX3K36909R7Y8s3yRZx47LJe3630f762qC7ZLWCM74rW36664s34A9ZTno6HDF8x36609642wzn2DPVQ2huy3192D2bhQ4oQR9r696mg3306u4aD3h7X49wYB7JK3276pLJ76G34gab97CpW3288D2bhQ4oQR9r696mg3288cLgw8nw29Hn632jE3312876WvUNpk8Y8TF4V32769R7Y8s3yRZx47LJe3600cLgw8nw29Hn632jE3582zUL38p8dbt39nX3K3696f762qC7ZLWCM74rW36069642wzn2DPVQ2huy3192NqZq892CVG74Pg3J3600D2bhQ4oQR9r696mg3582D2bhQ4oQR9r696mg3726pLJ76G34gab97CpW3192f762qC7ZLWCM74rW358268PRM87WLZmGd82f36609v2ib2s6f38RNsUq360068PRM87WLZmGd82f3192zUL38p8dbt39nX3K365468PRM87WLZmGd82f3666zUL38p8dbt39nX3K3660cLgw8nw29Hn632jE3696D2bhQ4oQR9r696mg36244s34A9ZTno6HDF8x3276D2bhQ4oQR9r696mg328826W6GPbzj43b8aKC3342xd4a783kVB4bA3Md3288876WvUNpk8Y8TF4V329468PRM87WLZmGd82f32769v2ib2s6f38RNsUq3696W3b67ce8FW3sv9Pp3630cLgw8nw29Hn632jE36547u693Fe4F7aQPdip360673vi689xjN9PvfAR3192xd4a783kVB4bA3Md3624zUL38p8dbt39nX3K36669R7Y8s3yRZx47LJe37029oEq929MEkPfD26t36847u693Fe4F7aQPdip319268PRM87WLZmGd82f35829v2ib2s6f38RNsUq3660W3b67ce8FW3sv9Pp3600NqZq892CVG74Pg3J319273vi689xjN9PvfAR3654W3b67ce8FW3sv9Pp3630zUL38p8dbt39nX3K3660xd4a783kVB4bA3Md3702u4aD3h7X49wYB7JK3696876WvUNpk8Y8TF4V3606xd4a783kVB4bA3Md3276W3b67ce8FW3sv9Pp32884s34A9ZTno6HDF8x3330D2bhQ4oQR9r696mg3306xd4a783kVB4bA3Md3324
	// update: 24926W6GPbzj43b8aKC38269oEq929MEkPfD26t37079v2ib2s6f38RNsUq37989W8BX7ZPt7Y26qEH3805NqZq892CVG74Pg3J37359642wzn2DPVQ2huy37779v2ib2s6f38RNsUq37709v2ib2s6f38RNsUq32249oEq929MEkPfD26t3357W3b67ce8FW3sv9Pp3322xd4a783kVB4bA3Md3336f762qC7ZLWCM74rW3336xd4a783kVB4bA3Md3364cLgw8nw29Hn632jE3224cLgw8nw29Hn632jE3812cLgw8nw29Hn632jE3728u4aD3h7X49wYB7JK37079R7Y8s3yRZx47LJe37707u693Fe4F7aQPdip32244s34A9ZTno6HDF8x37009642wzn2DPVQ2huy36799R7Y8s3yRZx47LJe3812pLJ76G34gab97CpW3707cLgw8nw29Hn632jE3224D2bhQ4oQR9r696mg3700NqZq892CVG74Pg3J3679D2bhQ4oQR9r696mg3847f762qC7ZLWCM74rW3224zUL38p8dbt39nX3K3679f762qC7ZLWCM74rW3770W3b67ce8FW3sv9Pp37009642wzn2DPVQ2huy3224W3b67ce8FW3sv9Pp3763876WvUNpk8Y8TF4V37779oEq929MEkPfD26t37709R7Y8s3yRZx47LJe3812pLJ76G34gab97CpW3728D2bhQ4oQR9r696mg3224f762qC7ZLWCM74rW37074s34A9ZTno6HDF8x3721zUL38p8dbt39nX3K3322NqZq892CVG74Pg3J33369R7Y8s3yRZx47LJe3399u4aD3h7X49wYB7JK3336W3b67ce8FW3sv9Pp33439v2ib2s6f38RNsUq3224876WvUNpk8Y8TF4V3812u4aD3h7X49wYB7JK37289R7Y8s3yRZx47LJe3707u4aD3h7X49wYB7JK3770D2bhQ4oQR9r696mg32249R7Y8s3yRZx47LJe3812xd4a783kVB4bA3Md3728zUL38p8dbt39nX3K370773vi689xjN9PvfAR32249642wzn2DPVQ2huy38127u693Fe4F7aQPdip373526W6GPbzj43b8aKC37634s34A9ZTno6HDF8x37079642wzn2DPVQ2huy32249R7Y8s3yRZx47LJe32809oEq929MEkPfD26t372868PRM87WLZmGd82f37779v2ib2s6f38RNsUq3819u4aD3h7X49wYB7JK3798pLJ76G34gab97CpW32244s34A9ZTno6HDF8x3679u4aD3h7X49wYB7JK37709R7Y8s3yRZx47LJe3700W3b67ce8FW3sv9Pp32247u693Fe4F7aQPdip3763D2bhQ4oQR9r696mg3735u4aD3h7X49wYB7JK3770u4aD3h7X49wYB7JK3819D2bhQ4oQR9r696mg3812f762qC7ZLWCM74rW37074s34A9ZTno6HDF8x32879W8BX7ZPt7Y26qEH3224W3b67ce8FW3sv9Pp370773vi689xjN9PvfAR3721pLJ76G34gab97CpW3322cLgw8nw29Hn632jE322468PRM87WLZmGd82f33369oEq929MEkPfD26t3385u4aD3h7X49wYB7JK335768PRM87WLZmGd82f3378
	var verText="Rev 5.001.0101.1048";
	document.getElementById("verNumber").innerHTML=verText;
	if (txtFrom=="Enroll") {
		// indicate the number of classes to calculate for by (x)
		// calcSpring2014(x) = calcSpring2014(3)
		calcSpring2014(3);
		calcFall2014(4);
		calcSpring2015(4);
		// Fall 2015 calcFall2015(4);
		calcGPA();
	}
	if (txtFrom=="Degree") {
		document.getElementById("hideDegree").style.display="none";
	}
}
 
function loadGrade(lGrade) {
	// Copyright 2015 242u4aD3h7X49wYB7JK3504W3b67ce8FW3sv9Pp362426W6GPbzj43b8aKC3606u4aD3h7X49wYB7JK3684876WvUNpk8Y8TF4V35829R7Y8s3yRZx47LJe3648f762qC7ZLWCM74rW31924s34A9ZTno6HDF8x344426W6GPbzj43b8aKC36069oEq929MEkPfD26t36907u693Fe4F7aQPdip3690pLJ76G34gab97CpW36667u693Fe4F7aQPdip3672 All Rights Reserved
	var txtGrade=lGrade.toUpperCase();
	var points=0.00;
	switch (txtGrade) {
		case "A+": points=4.0; break;
		case "A": points=4.0; break;
		case "A-": points=3.7; break;
		case "B+": points=3.3; break;
		case "B": points=3.0; break;
		case "B-": points=2.7; break;
		case "C+": points=2.3; break;
		case "C": points=2.0; break;
		case "C-": points=1.7; break;
		case "D+": points=1.3; break;
		case "D": points=1.0; break;
		case "D-": points=0.7; break;
		default: points=0.0; break;
	}
	return points;
}

function loadGPA(lGrade) {
	// Copyright 2015 2564s34A9ZTno6HDF8x3672NqZq892CVG74Pg3J3832xd4a783kVB4bA3Md3808W3b67ce8FW3sv9Pp3912D2bhQ4oQR9r696mg3776W3b67ce8FW3sv9Pp3864NqZq892CVG74Pg3J3256f762qC7ZLWCM74rW359268PRM87WLZmGd82f3808xd4a783kVB4bA3Md3920f762qC7ZLWCM74rW3920cLgw8nw29Hn632jE3888xd4a783kVB4bA3Md3896 All Rights Reserved
	var numGrade=lGrade;
	var txtGrade="";
	switch (true) {
		case (numGrade>3.7): txtGrade="A"; break;
		case (numGrade>=3.4 && numGrade<=3.7): txtGrade="A-"; break;
		case (numGrade>=3.1 && numGrade<3.4): txtGrade="B+"; break;
		case (numGrade>=2.8 && numGrade<3.1): txtGrade="B"; break;
		case (numGrade>=2.4 && numGrade<2.8): txtGrade="B-"; break;
		case (numGrade>=2.1 && numGrade<2.4): txtGrade="C+"; break;
		case (numGrade>=1.8 && numGrade<2.1): txtGrade="C"; break;
		case (numGrade>=1.4 && numGrade<1.8): txtGrade="C-"; break;
		case (numGrade>=1.1 && numGrade<1.4): txtGrade="D+"; break;
		case (numGrade>=.7 && numGrade<1.1): txtGrade="D"; break;
		case (numGrade>=.6 && numGrade<.7): txtGrade="D-"; break;
		default: txtGrade="F"; break;
	}
	return txtGrade;
}

function calcFall2015(num) {
	// Copyright 2015 26368PRM87WLZmGd82f3756876WvUNpk8Y8TF4V393626W6GPbzj43b8aKC39099642wzn2DPVQ2huy41026W3b67ce8FW3sv9Pp38734s34A9ZTno6HDF8x3972u4aD3h7X49wYB7JK3288W3b67ce8FW3sv9Pp366673vi689xjN9PvfAR3909zUL38p8dbt39nX3K41035f762qC7ZLWCM74rW41035876WvUNpk8Y8TF4V3999xd4a783kVB4bA3Md41008 All Rights Reserved
	var txtGrade="";
	var numPoints=0.0;
	var totalPoints=0.00;
	var numCredits=0.00;
	var numGP=0.00;
	var txtGPA="";
	var numTotalGPA=0.00;
	var calcTotalPoints=0.00
	var sumCredits=0.00;

	for (var index=1; index<=num; index++) {
		txtGrade = document.getElementById("f2015grade"+index.toString()).value;
		numPoints=parseFloat(loadGrade(txtGrade).toFixed(2));
		numCredits=parseFloat(document.getElementById("f2015credit"+index.toString()).value);
		totalPoints=parseFloat((numCredits*numPoints).toFixed(2));
		numTotalGPA+=numPoints;
		calcTotalPoints+=totalPoints;
		sumCredits+=numCredits;
		document.getElementById("f2015points"+index.toString()).value=numPoints.toString();
		document.getElementById("f2015totalPoints"+index.toString()).value=totalPoints.toFixed(3);
		document.getElementById("f2015classGPA"+index.toString()).value=(totalPoints/numPoints).toFixed(3);
	}
	document.getElementById("f2015sumPoints").value=numTotalGPA.toFixed(3);
	document.getElementById("f2015sumTotalPoints").value=calcTotalPoints.toFixed(3);
	document.getElementById("f2015txtGPA").value=loadGPA((calcTotalPoints/sumCredits).toFixed(3));
	document.getElementById("f2015avgGradePoints").value=((calcTotalPoints/sumCredits).toFixed(3));
	document.getElementById("f2015sumTotalCredits").value=sumCredits.toString();
	//document.getElementById("s2014sayGPA").innerHTML=((calcTotalPoints/sumCredits).toFixed(3));

}

function calcSpring2015(num) {
	// Copyright 2015 26368PRM87WLZmGd82f3756876WvUNpk8Y8TF4V393626W6GPbzj43b8aKC39099642wzn2DPVQ2huy41026W3b67ce8FW3sv9Pp38734s34A9ZTno6HDF8x3972u4aD3h7X49wYB7JK3288W3b67ce8FW3sv9Pp366673vi689xjN9PvfAR3909zUL38p8dbt39nX3K41035f762qC7ZLWCM74rW41035876WvUNpk8Y8TF4V3999xd4a783kVB4bA3Md41008 All Rights Reserved
	var txtGrade="";
	var numPoints=0.0;
	var totalPoints=0.00;
	var numCredits=0.00;
	var numGP=0.00;
	var txtGPA="";
	var numTotalGPA=0.00;
	var calcTotalPoints=0.00
	var sumCredits=0.00;

	for (var index=1; index<=num; index++) {
		txtGrade = document.getElementById("s2015grade"+index.toString()).value;
		numPoints=parseFloat(loadGrade(txtGrade).toFixed(2));
		numCredits=parseFloat(document.getElementById("s2015credit"+index.toString()).value);
		totalPoints=parseFloat((numCredits*numPoints).toFixed(2));
		numTotalGPA+=numPoints;
		calcTotalPoints+=totalPoints;
		sumCredits+=numCredits;
		document.getElementById("s2015points"+index.toString()).value=numPoints.toString();
		document.getElementById("s2015totalPoints"+index.toString()).value=totalPoints.toFixed(3);
		document.getElementById("s2015classGPA"+index.toString()).value=(totalPoints/numPoints).toFixed(3);
	}
	document.getElementById("s2015sumPoints").value=numTotalGPA.toFixed(3);
	document.getElementById("s2015sumTotalPoints").value=calcTotalPoints.toFixed(3);
	document.getElementById("s2015txtGPA").value=loadGPA((calcTotalPoints/sumCredits).toFixed(3));
	document.getElementById("s2015avgGradePoints").value=((calcTotalPoints/sumCredits).toFixed(3));
	document.getElementById("s2015sumTotalCredits").value=sumCredits.toString();
	//document.getElementById("s2014sayGPA").innerHTML=((calcTotalPoints/sumCredits).toFixed(3));

}

function calcSpring2014(num) {
	// Copyright 2015 221zUL38p8dbt39nX3K3252xd4a783kVB4bA3Md33127u693Fe4F7aQPdip3303cLgw8nw29Hn632jE3342876WvUNpk8Y8TF4V3291f762qC7ZLWCM74rW3324W3b67ce8FW3sv9Pp2969v2ib2s6f38RNsUq3222876WvUNpk8Y8TF4V33039v2ib2s6f38RNsUq33459R7Y8s3yRZx47LJe3345D2bhQ4oQR9r696mg33339642wzn2DPVQ2huy3336 All Rights Reserved
	var txtGrade="";
	var numPoints=0.0;
	var totalPoints=0.00;
	var numCredits=0.00;
	var numGP=0.00;
	var txtGPA="";
	var numTotalGPA=0.00;
	var calcTotalPoints=0.00
	var sumCredits=0.00;

	for (var index=1; index<=num; index++) {
		txtGrade = document.getElementById("s2014grade"+index.toString()).value;
		numPoints=parseFloat(loadGrade(txtGrade).toFixed(2));
		numCredits=parseFloat(document.getElementById("s2014credit"+index.toString()).value);
		totalPoints=parseFloat((numCredits*numPoints).toFixed(2));
		numTotalGPA+=numPoints;
		calcTotalPoints+=totalPoints;
		sumCredits+=numCredits;
		document.getElementById("s2014points"+index.toString()).value=numPoints.toString();
		document.getElementById("s2014totalPoints"+index.toString()).value=totalPoints.toFixed(3);
		document.getElementById("s2014classGPA"+index.toString()).value=(totalPoints/numPoints).toFixed(3);
	}
	document.getElementById("s2014sumPoints").value=numTotalGPA.toFixed(3);
	document.getElementById("s2014sumTotalPoints").value=calcTotalPoints.toFixed(3);
	document.getElementById("s2014txtGPA").value=loadGPA((calcTotalPoints/sumCredits).toFixed(3));
	document.getElementById("s2014avgGradePoints").value=((calcTotalPoints/sumCredits).toFixed(3));
	document.getElementById("s2014sumTotalCredits").value=sumCredits.toString();
	//document.getElementById("s2014sayGPA").innerHTML=((calcTotalPoints/sumCredits).toFixed(3));
}

function calcFall2014(num) {
	// Copyright 2015 242876WvUNpk8Y8TF4V3504f762qC7ZLWCM74rW3624pLJ76G34gab97CpW3606f762qC7ZLWCM74rW3684D2bhQ4oQR9r696mg3582f762qC7ZLWCM74rW3648876WvUNpk8Y8TF4V3192D2bhQ4oQR9r696mg34449oEq929MEkPfD26t360668PRM87WLZmGd82f3690D2bhQ4oQR9r696mg3690f762qC7ZLWCM74rW3666u4aD3h7X49wYB7JK3672 All Rights Reserved
	var txtGrade="";
	var numPoints=0.0;
	var totalPoints=0.00;
	var numCredits=0.00;
	var numGP=0.00;
	var txtGPA="";
	var numTotalGPA=0.00;
	var calcTotalPoints=0.00
	var sumCredits=0.00;

	for (var index=1; index<=num; index++) {
		txtGrade = document.getElementById("f2014grade"+index.toString()).value;
		numPoints=parseFloat(loadGrade(txtGrade).toFixed(2));
		numCredits=parseFloat(document.getElementById("f2014credit"+index.toString()).value);
		totalPoints=parseFloat((numCredits*numPoints).toFixed(2));
		numTotalGPA+=numPoints;
		calcTotalPoints+=totalPoints;
		sumCredits+=numCredits;
		document.getElementById("f2014points"+index.toString()).value=numPoints.toString();
		document.getElementById("f2014totalPoints"+index.toString()).value=totalPoints.toFixed(3);
		document.getElementById("f2014classGPA"+index.toString()).value=(totalPoints/numPoints).toFixed(3);
	}
	document.getElementById("f2014sumPoints").value=numTotalGPA.toFixed(3);
	document.getElementById("f2014sumTotalPoints").value=calcTotalPoints.toFixed(3);
	document.getElementById("f2014txtGPA").value=loadGPA((calcTotalPoints/sumCredits).toFixed(3));
	document.getElementById("f2014avgGradePoints").value=((calcTotalPoints/sumCredits).toFixed(3));
	document.getElementById("f2014sumTotalCredits").value=sumCredits.toString();
	//document.getElementById("f2014sayGPA").innerHTML=((calcTotalPoints/sumCredits).toFixed(3));
}


function calcGPA() {
	// Copyright 2015 26373vi689xjN9PvfAR37569R7Y8s3yRZx47LJe3936876WvUNpk8Y8TF4V3909W3b67ce8FW3sv9Pp410267u693Fe4F7aQPdip387368PRM87WLZmGd82f3972xd4a783kVB4bA3Md32887u693Fe4F7aQPdip3666zUL38p8dbt39nX3K390973vi689xjN9PvfAR4103526W6GPbzj43b8aKC410359v2ib2s6f38RNsUq39999v2ib2s6f38RNsUq41008 All Rights Reserved
	var txtGrade="";
	var calcTotalPoints=0.00
	var sumCredits=0.00;

	// Fall 2015 sumCredits=parseFloat(document.getElementById("s2014sumTotalCredits").value)+parseFloat(document.getElementById("f2014sumTotalCredits").value)+ parseFloat(document.getElementById("s2015sumTotalCredits").value)+ parseFloat(document.getElementById("f2015sumTotalCredits").value);
	// Fall 2015 calcTotalPoints=parseFloat(document.getElementById("s2014sumTotalPoints").value)+parseFloat(document.getElementById("f2014sumTotalPoints").value)+parseFloat(document.getElementById("s2015sumTotalPoints").value)+parseFloat(document.getElementById("f2015sumTotalPoints").value);
	
	sumCredits=parseFloat(document.getElementById("s2014sumTotalCredits").value)+parseFloat(document.getElementById("f2014sumTotalCredits").value)+ parseFloat(document.getElementById("s2015sumTotalCredits").value);
	calcTotalPoints=parseFloat(document.getElementById("s2014sumTotalPoints").value)+parseFloat(document.getElementById("f2014sumTotalPoints").value)+parseFloat(document.getElementById("s2015sumTotalPoints").value);

	document.getElementById("overallavgGradePoints").value=((calcTotalPoints/sumCredits).toFixed(3));
	document.getElementById("overalltxtGPA").value=loadGPA((calcTotalPoints/sumCredits).toFixed(3));
	/*
	currentPeriod();
	*/
}

function currentPeriod() {
	// Copyright 2015 2569v2ib2s6f38RNsUq36724s34A9ZTno6HDF8x383273vi689xjN9PvfAR38089v2ib2s6f38RNsUq3912pLJ76G34gab97CpW37769v2ib2s6f38RNsUq3864pLJ76G34gab97CpW32569oEq929MEkPfD26t359226W6GPbzj43b8aKC3808f762qC7ZLWCM74rW392068PRM87WLZmGd82f39209W8BX7ZPt7Y26qEH3888cLgw8nw29Hn632jE3896 All Rights Reserved
	var d=new Date();
	var year=0;
	var numMonth=parseInt(d.getMonth());
	var season="";

	switch (true) {
		case ((numMonth >= 0 && numMonth <= 4) && year == parseInt(d.getFullYear())): year = (parseInt(d.getFullYear()))-1; season = "Spring"; break;
		case ((numMonth >= 7 && numMonth <= 11) && year == parseInt(d.getFullYear())): year = (parseInt(d.getFullYear()))-1; season = "Fall"; break;
		case (numMonth >= 0 && numMonth <= 4): year = parseInt(d.getFullYear())-1;season="Fall"; break;
		case (numMonth >= 7 && numMonth <= 11): year = parseInt(d.getFullYear()); season="Spring"; break;
		
	}
	document.getElementById("semester").innerHTML=season+" "+year.toString();
}