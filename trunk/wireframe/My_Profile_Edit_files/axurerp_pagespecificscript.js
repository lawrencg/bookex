
var PageName = 'My Profile Edit';
var PageId = 'pd18effd42e184ecabfc6138b1f95715a'
var PageUrl = 'My_Profile_Edit.html'
document.title = 'My Profile Edit';

if (top.location != self.location)
{
	if (parent.HandleMainFrameChanged) {
		parent.HandleMainFrameChanged();
	}
}

var $OnLoadVariable = '';

var $CSUM;

var hasQuery = false;
var query = window.location.hash.substring(1);
if (query.length > 0) hasQuery = true;
var vars = query.split("&");
for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    if (pair[0].length > 0) eval("$" + pair[0] + " = decodeURIComponent(pair[1]);");
} 

if (hasQuery && $CSUM != 1) {
alert('Prototype Warning: The variable values were too long to pass to this page.\nIf you are using IE, using Firefox will support more data.');
}

function GetQuerystring() {
    return '#OnLoadVariable=' + encodeURIComponent($OnLoadVariable) + '&CSUM=1';
}

function PopulateVariables(value) {
  value = value.replace(/\[\[OnLoadVariable\]\]/g, $OnLoadVariable);
  value = value.replace(/\[\[PageName\]\]/g, PageName);
  return value;
}

function OnLoad(e) {

}

var u20 = document.getElementById('u20');
gv_vAlignTable['u20'] = 'top';
var u36 = document.getElementById('u36');

var u31 = document.getElementById('u31');
gv_vAlignTable['u31'] = 'center';
var u11 = document.getElementById('u11');

var u27 = document.getElementById('u27');
gv_vAlignTable['u27'] = 'top';
var u6 = document.getElementById('u6');
gv_vAlignTable['u6'] = 'top';
var u4 = document.getElementById('u4');
gv_vAlignTable['u4'] = 'top';
var u2 = document.getElementById('u2');

u2.style.cursor = 'pointer';
if (bIE) u2.attachEvent("onclick", Clicku2);
else u2.addEventListener("click", Clicku2, true);
function Clicku2(e)
{

if (true) {

	self.location.href="Logout.html" + GetQuerystring();

}

}
gv_vAlignTable['u2'] = 'top';
var u10 = document.getElementById('u10');

u10.style.cursor = 'pointer';
if (bIE) u10.attachEvent("onclick", Clicku10);
else u10.addEventListener("click", Clicku10, true);
function Clicku10(e)
{

if (true) {

	self.location.href="My_Profile.html" + GetQuerystring();

}

}
gv_vAlignTable['u10'] = 'top';
var u0 = document.getElementById('u0');

var u26 = document.getElementById('u26');
gv_vAlignTable['u26'] = 'top';
var u35 = document.getElementById('u35');

var u29 = document.getElementById('u29');
gv_vAlignTable['u29'] = 'top';
var u8 = document.getElementById('u8');
gv_vAlignTable['u8'] = 'top';
var u34 = document.getElementById('u34');

var u14 = document.getElementById('u14');
gv_vAlignTable['u14'] = 'center';
var u28 = document.getElementById('u28');
gv_vAlignTable['u28'] = 'top';
var u33 = document.getElementById('u33');

var u22 = document.getElementById('u22');
gv_vAlignTable['u22'] = 'center';
var u13 = document.getElementById('u13');

var u12 = document.getElementById('u12');
gv_vAlignTable['u12'] = 'center';
var u21 = document.getElementById('u21');

var u37 = document.getElementById('u37');

u37.style.cursor = 'pointer';
if (bIE) u37.attachEvent("onclick", Clicku37);
else u37.addEventListener("click", Clicku37, true);
function Clicku37(e)
{

if (true) {

	self.location.href="My_Profile.html" + GetQuerystring();

}

}

var u7 = document.getElementById('u7');
gv_vAlignTable['u7'] = 'top';
var u17 = document.getElementById('u17');

var u5 = document.getElementById('u5');
gv_vAlignTable['u5'] = 'top';
var u15 = document.getElementById('u15');

u15.style.cursor = 'pointer';
if (bIE) u15.attachEvent("onclick", Clicku15);
else u15.addEventListener("click", Clicku15, true);
function Clicku15(e)
{

if (true) {

	self.location.href="Dashboard.html" + GetQuerystring();

}

}
gv_vAlignTable['u15'] = 'top';
var u3 = document.getElementById('u3');
gv_vAlignTable['u3'] = 'top';
var u1 = document.getElementById('u1');
gv_vAlignTable['u1'] = 'center';
var u25 = document.getElementById('u25');
gv_vAlignTable['u25'] = 'top';
var u16 = document.getElementById('u16');
gv_vAlignTable['u16'] = 'top';
var u19 = document.getElementById('u19');

u19.style.cursor = 'pointer';
if (bIE) u19.attachEvent("onclick", u19Click);
else u19.addEventListener("click", u19Click, true);
InsertAfterBegin(document.body, "<DIV class='intcases' id='u19LinksClick'></DIV>")
var u19LinksClick = document.getElementById('u19LinksClick');
function u19Click(e) 
{

	ToggleLinks(e, 'u19LinksClick');
}

InsertBeforeEnd(u19LinksClick, "<div class='intcaselink' onmouseout='SuppressBubble(event)' onclick='u19Clickua635d516fc90407fb38bbf3abc9bfd73(event)'>Book Search</div>");
function u19Clickua635d516fc90407fb38bbf3abc9bfd73(e)
{

	self.location.href="Search_Book_Results.html" + GetQuerystring();

	ToggleLinks(e, 'u19LinksClick');
}

InsertBeforeEnd(u19LinksClick, "<div class='intcaselink' onmouseout='SuppressBubble(event)' onclick='u19Clicku6777edb68a1945899d04972234558e73(event)'>People Search</div>");
function u19Clicku6777edb68a1945899d04972234558e73(e)
{

	self.location.href="Search_People_Results.html" + GetQuerystring();

	ToggleLinks(e, 'u19LinksClick');
}

var u9 = document.getElementById('u9');

u9.style.cursor = 'pointer';
if (bIE) u9.attachEvent("onclick", Clicku9);
else u9.addEventListener("click", Clicku9, true);
function Clicku9(e)
{

if (true) {

	self.location.href="My_Book.html" + GetQuerystring();

}

}
gv_vAlignTable['u9'] = 'top';
var u30 = document.getElementById('u30');

var u24 = document.getElementById('u24');
gv_vAlignTable['u24'] = 'center';
var u38 = document.getElementById('u38');

var u18 = document.getElementById('u18');

var u32 = document.getElementById('u32');

var u23 = document.getElementById('u23');

if (window.OnLoad) OnLoad();
