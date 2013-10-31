var schema = 1;
if(document.cookie.indexOf("schema")!=-1)
    schema = parseInt(document.cookie.charAt(document.cookie.indexOf("schema")+7), 10);

function set_scheme(c)
{
    if(c>3 || c<1 || isNaN(c)) c=1; //len 3 platne schemy su
    var datum = new Date();
    datum.setTime(datum.getTime()+10*24*60*60*1000);   //platnost 10 dni (v milisekundach)
    document.cookie="schema="+c+"; expires="+datum.toUTCString()+"; path=/";  //nastavi cookie
    for (j=1;j<=3;j++)  //nastavi stylesheet a ikonu schemy
    {
        var ico = document.getElementById('img'+j);
        if (j!=c)
        {
            document.styleSheets[j].disabled=true;
            ico.src="/img/sch"+j+".png";
        }
        else
        {
            document.styleSheets[j].disabled=false;
            ico.src="/img/sch"+j+"_big.png";
        }
    }
}

function box_show(box,message)
{
  box.style.display="block";
  box.innerHTML=message;
}

function spracuj_reg()
{
    var login = document.forms['reg_form']['user[login]'].value;
    var heslo = document.forms['reg_form']['user[heslo]'].value;
    var heslo2 = document.forms['reg_form']['user[heslo2]'].value;
    var web = document.forms['reg_form']['user[web]'].value;
    var captcha = document.forms['reg_form']['user[captcha]'].value;
    var errorbox = document.getElementById("reg_errbox");

    if(login=="" || heslo=="" || heslo2=="" || web=="" || captcha=="")
    {
        box_show(errorbox,"Vyplňte všetky údaje!");
        return;
    }
    if (!(/^[a-zA-Z0-9]{4,10}$/).test(login))
    {
        box_show(errorbox, "Login má nesprávny formát! (4-10 znakov: a-z, A-Z, 0-9)");
        return;
    }
    if(heslo != heslo2)
    {
        box_show(errorbox,"Heslá sa nezhodujú!");
        return;
    }
    if(heslo.length<4 || login.length<4)
    {
        box_show(errorbox,"Heslo musí mať aspoň 4 znaky!");
        return;
    }
    if(heslo.indexOf("\"")>=0 || heslo.indexOf("\'")>=0)
    {
        box_show(errorbox,"Heslo nesmie obsahovať \" ani \'!");
        return;
    }
    document.forms['reg_form'].submit();
}

function spracuj_log()
{
    var login = document.forms['log_form'].login.value;
    var heslo = document.forms['log_form'].heslo.value;
    var errorbox = document.getElementById("log_errbox");

    if(login=="" || heslo=="")
    {
        box_show(errorbox,"Vplňte obe polia!");
        return;
    }
    document.forms['log_form'].submit();
}

function spracuj_add()
{
    var size = document.forms['add_form'].velkost.value;
    var meno = document.forms['add_form'].meno.value;
    var categories = document.forms['add_form']['kategorie[]'].value; //lebo name je 'kategorie[]'
    var errorbox = document.getElementById("add_errbox");

    if(size=="")
    {
        box_show(errorbox,"Zvoľte typ reklamy!");
        return;
    }
    if(meno=="")
    {
        box_show(errorbox,"Zvoľte názov popisujúci reklamu!");
        return;
    }
    if(meno.indexOf("\"")>=0 || meno.indexOf("\'")>=0 || meno.indexOf("\\")>=0)
    {
        box_show(errorbox,"Názov nesmie obsahovať: \", \', \\");
        return;
    }
    if(categories.length==0)
    {
        box_show(errorbox,"Zvoľte kategóriu(e)!");
        return;
    }
    document.forms['add_form'].submit();
}

function spracuj_upl()
{
    var size = document.forms['upl_form'].velkost.value;
    var file = document.forms['upl_form'].userfile.value;
    var categories = document.forms['upl_form']['kategorie[]'].value; //lebo name je 'kategorie[]'
    var errorbox = document.getElementById("upl_errbox");

    if(size=="")
    {
        box_show(errorbox,"Zvoľte typ banneru!");
        return;
    }
    if(file=="")
    {
        box_show(errorbox,"Zvoľte súbor s bannerom!");
        return;
    }
    if(categories.length==0)
    {
        box_show(errorbox,"Zvoľte kategóriu(e)!");
        return;
    }
    document.forms['upl_form'].submit();
}

function spracuj_filter()
{
    var odDatum = new Date();
    odDatum.setFullYear(document.forms['filter'].odYear.value, document.forms['filter'].odMonth.value, document.forms['filter'].odDay.value);
    var doDatum = new Date();
    doDatum.setFullYear(document.forms['filter'].doYear.value, document.forms['filter'].doMonth.value, document.forms['filter'].doDay.value);
    if(odDatum>doDatum && document.forms['filter'].date.value=='custom')
    {
        box_show(document.getElementById('filter_errbox'),"Začiatok obdobia je neskôr ako koniec!")
        return;
    }
    document.forms['filter'].page.value = 1;
    document.forms['filter'].submit();
}

function setPage(p)
{
    document.forms['filter'].page.value = p;
    document.forms['filter'].submit();
}

//AJAX ---------------------------------------------------------------------------------------------

function spracuj_chweb()
{
    var web = document.forms['chweb_form'].web.value;
    var csrf_token = document.forms['chweb_form'].csrf_token.value;
    var errorbox = document.getElementById("chweb_errbox");
    var okbox = document.getElementById("chweb_okbox");
    errorbox.style.display = 'none';
    okbox.style.display = 'none';

    if(web=="")
    {
        box_show(errorbox,"Zadajte adresu!");
        return;
    }
    var xmlhttp = GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert("Váš prehliadač nepodporuje XMLHTTP (AjaX)!");
        return;
    }
    var url="/ajax/chweb";
    var params ="web="+encodeURIComponent(web)+"&csrf_token="+encodeURIComponent(csrf_token);
    params+="&sid="+Math.random();  //Adds a random number to prevent the server from using a cached file
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4)
        {
            var resp = eval('('+xmlhttp.responseText+')');
            box_show(resp.success?okbox:errorbox,resp.message);
            if(resp.success)
                document.getElementById('webTd').innerHTML = "http://"+web;
        }
    };
    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.send(params);
}

function spracuj_chpas()
{
    var old = document.forms['chpas_form'].old.value;
    var new1 = document.forms['chpas_form'].new1.value;
    var new2 = document.forms['chpas_form'].new2.value;
    var csrf_token = document.forms['chpas_form'].csrf_token.value;
    var errorbox = document.getElementById("chpas_errbox");
    var okbox = document.getElementById("chpas_okbox");
    errorbox.style.display = 'none';
    okbox.style.display = 'none';

    if(old=="" || new1=="" || new2=="")
    {
        box_show(errorbox,"Vyplňte všetky údaje!");
        return;
    }
    if(new1 != new2)
    {
        box_show(errorbox,"Heslo sa nezhoduje!");
        return;
    }
    if(new1.length<4)
    {
        box_show(errorbox,"Heslo musí mať aspoň 4 znaky!");
        return;
    }
    if(new1.indexOf("\"")>=0 || new1.indexOf("\'")>=0 || new1.indexOf("\\")>=0)
    {
        box_show(errorbox,"Heslo nesmie obsahovať: \", \', \\");
        return;
    }
    var xmlhttp = GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert("Váš prehliadač nepodporuje XMLHTTP (AjaX)!");
        return;
    }
    var url = "/ajax/chpas";
    var params = "old="+encodeURIComponent(old)+"&new="+encodeURIComponent(new1)+"&csrf_token="+encodeURIComponent(csrf_token);
    params += "&sid="+Math.random();  //Adds a random number to prevent the server from using a cached file
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4)
        {
            var resp = eval('('+xmlhttp.responseText+')');
            box_show(resp.success?okbox:errorbox,resp.message);
        }
    };
    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");  //nutne lebo default je text/plain a to by nesiel POST
    //xmlhttp.setRequestHeader("Content-length", params.length); //doplni browser (aj encoding do content-type)
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.send(params);
}

function overLogin(str)
{
    var loginStatus =  document.getElementById("loginStatus");
    loginStatus.className = "";
    loginStatus.innerHTML="Overujem...";

    if (!(/^[a-zA-Z\d]{4,10}$/).test(str))
    {
        document.getElementById("loginStatus").innerHTML="<span class='r'>Nesprávny formát! (aspoň 4 znaky: a-z, A-Z, 0-9)</span>";
        return;
    }
    var xmlhttp = GetXmlHttpObject();
    if (xmlhttp==null)
    {
        alert("Váš prehliadač nepodporuje XMLHTTP (AjaX)!");
        return;
    }
    var url = "ajax/checklogin";
    var params = "login="+encodeURIComponent(str);
    params += "&sid="+Math.random();  //Adds a random number to prevent the server from using a cached file
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState==4)
        {
            var resp = eval('('+xmlhttp.responseText+')');
            loginStatus.className = resp.success?"g":"r";
            loginStatus.innerHTML = resp.message;
        }
    };
    xmlhttp.open("GET",url+'?'+params, true);
    xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xmlhttp.send(null);
}

function GetXmlHttpObject()
{
    if (window.XMLHttpRequest) // code for IE7+, Firefox, Chrome, Opera, Safari
        return new XMLHttpRequest();
    if (window.ActiveXObject)  // code for IE6, IE5
        return new ActiveXObject("Microsoft.XMLHTTP");
    return null;
}

//LAYOUT --------------------------------------------------------------------------------------------

function show(co,a)
{
    var tr = document.getElementById(co);

    if(tr.style.display=="none")
    {
        tr.style.display="table-row";
        a.innerHTML = "skry";

    }
    else
    {
        tr.style.display="none";
        a.innerHTML = "zmeň";
    }
}

function show2(co)
{
    var tr = document.getElementById(co);

    if(tr.style.display=="none")
        tr.style.display="table-row";
    else
        tr.style.display="none";
}

function nacitajGraf()
{
    var place = document.getElementById('graf_placeholder');
    //place.innerHTML = 'loading...';
    var img = document.createElement('img');
    img.setAttribute("id", "graf");
    img.onload = function(){
        img.alt = 'graf';
        place.removeChild(place.childNodes[0]);
        place.appendChild(this);
        addImage("graf", "graf_imageMap", "/img/graf?&imageMap");
    };
    img.onerror = function(){
        place.innerHTML = 'Chyba!';
    };
    img.src = '/img/graf?rand='+Math.random();
}